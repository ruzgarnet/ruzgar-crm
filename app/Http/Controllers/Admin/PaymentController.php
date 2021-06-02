<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Moka;
use App\Classes\Mutator;
use App\Http\Controllers\Controller;
use App\Models\MokaLog;
use App\Models\MokaPayment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Receives payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function received(Request $request, Payment $payment)
    {
        $rules = $this->rules();
        if (in_array($request->input('type'), [3, 4])) {
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
        }
        if ($request->input('type') == 3) {
            $rules["card.security_code"] = [
                'required',
                'numeric',
                'between:100,999'
            ];
        }

        $validated = $request->validate($rules);

        if ($request->input('type') == 3) {
            $expire = Mutator::expire_date($validated["card"]["expire_date"]);
            $card = [
                'full_name' => $validated["card"]['full_name'],
                'number' => $validated["card"]['number'],
                'expire_month' => $expire[0],
                'expire_year' => $expire[1],
                'security_code' => $validated["card"]['security_code'],
                'amount' => $payment->price
            ];
            $moka = new Moka();
            $response = $moka->pay(
                $card,
                route('admin.payment.result', $payment)
            );
            if ($response->Data != null) {
                return response()->json([
                    'success' => true,
                    'payment' => [
                        'frame' => $response->Data
                    ]
                ]);
            } else {
                MokaLog::create([
                    'payment_id' => $payment->id,
                    'ip' => $request->ip(),
                    'response' => $response
                ]);

                return response()->json([
                    'error' => true,
                    'toastr' => [
                        'type' => 'error',
                        'title' => trans('response.title.error'),
                        'message' => $response->ResultCode
                    ]
                ]);
            }
        } else {
            $date = Carbon::parse($payment->date);
            $month = Carbon::now()->format("m");

            if ($date->format("m") == $month) {
                $payment->receive_payment($validated);

                return response()->json([
                    'success' => true,
                    'toastr' => [
                        'type' => 'success',
                        'title' => trans('response.title.success'),
                        'message' => trans('warnings.payment.successful')
                    ],
                    'redirect' => relative_route('admin.subscription.payments', ['subscription' => $payment->subscription])
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'toastr' => [
                        'type' => 'error',
                        'title' => trans('response.title.error'),
                        'message' => trans('warnings.payment.not_allowed_received_date')
                    ]
                ]);
            }
        }
    }

    /**
     * Gets Moka payment result
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function payment_result(Request $request, Payment $payment)
    {
        $data = $request->all();
        $data["payment_id"] = $payment->id;
        MokaPayment::insert($data);
        // TODO Sunucuya aktarıldığında yapılacak.
    }

    private function rules()
    {
        return [
            'type' => [
                'required',
                Rule::in(Payment::getTypes())
            ]
        ];
    }
}
