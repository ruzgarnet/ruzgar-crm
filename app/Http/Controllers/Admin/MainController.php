<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FaultRecord;
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

    /**
     * Infrastructure page
     *
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Not permission page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function cant()
    {
        return view('admin.cant');
    }
}
