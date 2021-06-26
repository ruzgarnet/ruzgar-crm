<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Moka;
use App\Classes\Mutator;
use App\Classes\SMS_Api;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FaultRecord;
use App\Models\MokaLog;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $data = [
            'total' => [
                'customer' => Customer::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->count(),
                'subscription' => Subscription::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->count(),
                'faultRecord' => FaultRecord::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->count(),
                'payment' => Payment::whereBetween('date', [date('Y-m-d H:i', strtotime('first day of this month')), date('Y-m-d H:i', strtotime('first day of next month'))])->sum('price')
            ],
            'subscriptions' => Subscription::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->limit(20)->get()
        ];
        return view('admin.dashboard', $data);
    }

    public function infrastructure()
    {
        return view('admin.infrastructure');
    }

    /**
     * Searchs customer
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function search(Request $request)
    {
        $search = $request->input("q");

        $customer = new Customer();
        $fields = $customer->getFields();
        $fields[] = DB::raw("CONCAT(`first_name`, ' ', `last_name`)");
        $rows = $customer->where(function ($query) use ($fields, $search) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', "%{$search}%");
            }
        })->limit(10)->get();

        $data = [];

        if (count($rows)) {
            foreach ($rows as $row) {
                $data[] = [
                    'title' => $row->select_print,
                    'link' => route('admin.customer.show', $row)
                ];
            }
        }

        return $data;
    }

    public function create_pre_auth(Request $request, Payment $payment)
    {
        $rules["card.number"] = [
            'required',
            'numeric'
        ];
        $rules["card.full_name"] = [
            'required',
            'string',
            'max:255'
        ];
        $rules["card.expire_date"] = [
            'required',
            'string'
        ];
        $rules["card.security_code"] = [
            'required',
            'numeric',
            'between:100,999'
        ];

        $validated = $request->validate($rules);

        $expire = Mutator::expire_date($validated["card"]["expire_date"]);

        $card = [
            'full_name' => $validated["card"]['full_name'],
            'number' => $validated["card"]['number'],
            'expire_month' => $expire[0],
            'expire_year' => $expire[1],
            'security_code' => $validated['card']["security_code"],
            'amount' => 1
        ];

        $hash = [
            'subscription_no' => $payment->subscription->subscription_no,
            'payment_created_at' => $payment->created_at
        ];

        $moka_log = MokaLog::create([
            'payment_id' => $payment->id,
            'ip' => $request->ip(),
            'type' => 7,
            'response' => null,
            'trx_code' => null
        ]);

        $moka = new Moka();
        $response = $moka->pay(
            $card,
            route('admin.payment.pre.auth.result', $moka_log),
            $hash,
            [
                'is_pre_auth' => 1,
                'pre_auth_price' => 1
            ]
        );

        if ($response->Data != null) {
            $moka_log->update([
                'response' => $response,
                'trx_code' => $moka->trx_code
            ]);

            return response()->json([
                'success' => true,
                'payment' => [
                    'frame' => $response->Data->Url
                ]
            ]);
        } else {
            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => $response->ResultCode
                ]
            ]);
        }
    }
}
