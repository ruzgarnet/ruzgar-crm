<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Generator;
use App\Classes\Messages;
use App\Classes\Moka;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Jobs\CheckAutoPayments;
use App\Jobs\CreateAutoPayments;
use App\Models\Category;
use App\Models\Customer;
use App\Models\FaultRecord;
use App\Models\Message;
use App\Models\MokaAutoPayment;
use App\Models\MokaPayment;
use App\Models\MokaSale;
use App\Models\Payment;
use App\Models\SentMessage;
use App\Models\Subscription;
use App\Models\SubscriptionCancellation;
use App\Models\SubscriptionChange;
use App\Models\SubscriptionFreeze;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    /**
     * Dashboard page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [
            'total' => [
                'customer' => Customer::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->count(),
                'subscription' => Subscription::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->count(),
                'faultRecord' => FaultRecord::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->whereNotIn('status', [5, 6])->count(),
                'payment' => Payment::where('status', '2')
                    ->where('type', '<>', 6)
                    ->whereBetween('date', [date('Y-m-01'), date('Y-m-t')])
                    ->sum('price'),
            ],
            'subscriptions' => Subscription::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d')", date('Y-m-d'))->orderByDesc('approved_at')->limit(10)->get()
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

    /**
     * Report Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function report()
    {
        $start_date = date('Y-m-d H:i', strtotime('first day of this month'));
        $end_date = date('Y-m-d H:i', strtotime('first day of next month'));

        $dates = [$start_date, $end_date];

        $payments = Payment::whereBetween('date', $dates)
            ->orderBy('type')
            ->get();

        $totals = [
            'price' => 0,
            'paided' => 0,
            'not_paided' => 0,
            'auto' => 0,
            'no_auto' => 0,
            'penalty_price' => 0,
        ];

        $subscriptions = [
            'customers' => Customer::count(),
            'subscriptions' => Subscription::count(),
        ];

        $statusKeys = [
            0 => 'unapproved',
            1 => 'active',
            2 => 'changed',
            3 => 'canceled',
            4 => 'freezed'
        ];

        $subscriptionStatus = array_merge([0], Subscription::getStatus());
        foreach ($subscriptionStatus as $status) {
            $subscriptions[$statusKeys[$status]] = Subscription::where('status', $status)->count();
        }

        $subscriptions_monthly = [
            'customers' => Customer::whereBetween('created_at', $dates)->count(),
            'subscriptions' => Subscription::whereBetween('created_at', $dates)->count(),
            'unapproved' => Subscription::whereBetween('created_at', $dates)->where('status', 0)->count(),
            'active' => Subscription::whereBetween('created_at', $dates)->where('status', 1)->count(),
            'changed' => SubscriptionChange::whereBetween('created_at', $dates)->count(),
            'canceled' => SubscriptionCancellation::whereBetween('created_at', $dates)->count(),
            'freezed' => SubscriptionFreeze::whereBetween('created_at', $dates)->count()
        ];

        $counts = [
            'payments' => 0,
            'auto' => 0,
            'paided' => 0,
            'not_paided' => 0,
            'penalty' => 0,
            'penalty_paided' => 0,
            'penalty_not_paided' => 0
        ];

        $types = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
        ];

        $categoryTemp = [
            'totals' => [
                'price' => 0,
                'paided' => 0,
                'not_paided' => 0,
                'auto' => 0,
                'no_auto' => 0,
                'penalty_price' => 0,
            ],
            'counts' => [
                'payments' => 0,
                'auto' => 0,
                'paided' => 0,
                'not_paided' => 0,
                'penalty' => 0,
                'penalty_paided' => 0,
                'penalty_not_paided' => 0
            ],
            'types' => [
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0
            ]
        ];

        foreach ($payments as $payment) {
            $price = ceil($payment->price);
            $category = $payment->category;
            $type = $payment->type ?? 0;

            if (!isset($categories[$category])) {
                $categories[$category] = $categoryTemp;
            }

            if ($payment->isPaid()) {
                if ($type != 6) {
                    $totals['paided'] += $price;
                    $counts['paided']++;
                    $categories[$category]['totals']['paided'] += $price;
                    $categories[$category]['counts']['paided']++;
                }

                if ($payment->isPenalty()) {
                    $counts['penalty_paided']++;
                    $categories[$category]['counts']['penalty_paided']++;
                }
            } else {
                $totals['not_paided'] += $price;
                $counts['not_paided']++;
                $categories[$category]['totals']['not_paided'] += $price;
                $categories[$category]['counts']['not_paided']++;

                if ($payment->isPenalty()) {
                    $counts['penalty_not_paided']++;
                    $categories[$category]['counts']['penalty_not_paided']++;
                }
            }

            if ($payment->subscription->isAuto()) {
                $totals['auto'] += $price;
                $counts['auto']++;
                $categories[$category]['totals']['auto'] += $price;
                $categories[$category]['counts']['auto']++;
            } else {
                $totals['no_auto'] += $price;
                $categories[$category]['totals']['no_auto'] += $price;
            }


            if ($payment->isPenalty()) {
                $totals['penalty_price'] += $payment->penalty->penalty_price;
                $counts['penalty']++;
                $categories[$category]['totals']['penalty_price'] += $payment->penalty->penalty_price;
                $categories[$category]['counts']['penalty']++;
            }

            $types[$type] += $price;
            if ($type != 6) {
                $totals['price'] += $price;
                $counts['payments']++;
            }

            $categories[$category]['types'][$type] += $price;
            if ($type != 6) {
                $categories[$category]['totals']['price'] += $price;
                $categories[$category]['counts']['payments']++;
            }
        }

        foreach ($categories as $categoryKey => $values) {
            $category = Category::where('key', $categoryKey)->first();

            $service_ids = $category->services()->pluck('id')->toArray();
            $sub_ids = Subscription::whereIn('service_id', $service_ids)->pluck('id')->toArray();

            $categories[$categoryKey]['subscriptions']['subscriptions'] = Subscription::whereIn('service_id', $service_ids)->count();

            foreach ($subscriptionStatus as $status) {
                $categories[$categoryKey]['subscriptions'][$statusKeys[$status]] = Subscription::whereIn('service_id', $service_ids)->where('status', $status)->count();
            }

            $categories[$categoryKey]['subscriptions_monthly'] = [
                'subscriptions' => Subscription::whereIn('service_id', $service_ids)->whereBetween('created_at', $dates)->count(),
                'unapproved' => Subscription::whereIn('service_id', $service_ids)->whereBetween('created_at', $dates)->where('status', 0)->count(),
                'active' => Subscription::whereIn('service_id', $service_ids)->whereBetween('created_at', $dates)->where('status', 1)->count(),
                'changed' => SubscriptionChange::whereIn('subscription_id', $sub_ids)->whereBetween('created_at', $dates)->count(),
                'canceled' => SubscriptionCancellation::whereIn('subscription_id', $sub_ids)->whereBetween('created_at', $dates)->count(),
                'freezed' => SubscriptionFreeze::whereIn('subscription_id', $sub_ids)->whereBetween('created_at', $dates)->count()
            ];
        }

        $totals['avarage'] = $totals['price'] / $subscriptions['active'];

        foreach ($categories as $key => $category) {
            $categories[$key]['totals']['avarage'] = $category['totals']['price'] / $category['subscriptions']['active'];
        }

        return view('admin.report', [
            'reports' => compact('totals', 'counts', 'types', 'subscriptions', 'subscriptions_monthly'),
            'categories' => $categories
        ]);
    }
}
