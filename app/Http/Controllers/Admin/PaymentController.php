<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Moka;
use App\Classes\Mutator;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Message;
use App\Models\MokaAutoPayment;
use App\Models\MokaAutoPaymentDisable;
use App\Models\MokaLog;
use App\Models\MokaPayment;
use App\Models\MokaRefund;
use App\Models\MokaSale;
use App\Models\Payment;
use App\Models\PaymentCancellation;
use App\Models\PaymentCreate;
use App\Models\PaymentDelete;
use App\Models\SentMessage;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view(
            'admin.payment.list',
            [
                'statuses' => trans('tables.payment.status'),
                'categories' => Category::all()
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function listMonthly(Request $request)
    {
        $date = Carbon::parse($request->input('date') ?? 'now');
        $start_date = $date->startOfMonth()->toDateString();
        $end_date = $date->endOfMonth()->toDateString();

        $dates = [$start_date, $end_date];

        return view('admin.payment.monthly', [
            'payments' => Payment::whereBetween('date', $dates)->get(),
            'date' => $date->toDateString()
        ]);
    }

    /**
     * Get a listing of the resource.
     *
     * @return array
     */
    public function list(Request $request)
    {
        $data = [];

        $offset = $request->input('start');
        $limit = $request->input('length');
        $draw = $request->input('draw');
        $date = str_replace("\\", "", $request->input('columns.4.search.value'));
        $status = $request->input('columns.5.search.value');
        $type = $request->input('columns.6.search.value');
        $category_id = $request->input('columns.2.search.value');

        $payments = Payment::selectRaw("payments.*")->orderBy('payments.id', 'desc');
        $no = Payment::selectRaw("payments.*")->orderBy('payments.id', 'desc');

        if ($category_id != "") {
            $payments = $payments
                ->join('subscriptions', 'subscriptions.id', 'payments.subscription_id')
                ->whereRaw("subscriptions.service_id IN (SELECT id FROM services WHERE category_id = {$category_id})");

            $no = $no
                ->join('subscriptions', 'subscriptions.id', 'payments.subscription_id')
                ->whereRaw("subscriptions.service_id IN (SELECT id FROM services WHERE category_id = {$category_id})");
        }

        if ($date != "") {
            $payments = $payments->where("payments.date", $date);
            $no = $no->where("payments.date", $date);
        }

        if ($status != "") {
            $payments = $payments->where("payments.status", $status);
            $no = $no->where("payments.status", $status);
        }

        if ($type != "") {
            $payments = $payments->where("payments.type", $type);
            $no = $no->where("payments.type", $type);
        }

        $payments = $payments->offset($offset)->limit($limit)->get();
        $no = $no->get();


        foreach ($payments as $payment) {
            if ($payment->type) {
                $type = trans('tables.payment.types.' . $payment->type);
            } else {
                $type = "";
            }

            $data[] = [
                0 => $payment->id,
                1 => '<a href="' . route('admin.customer.show', $payment->subscription->customer_id) . '">' . $payment->subscription->customer->full_name . '</a>',
                2 => $payment->subscription->service->name,
                3 => print_money($payment->price),
                4 => convert_date($payment->date, "mask"),
                5 => trans('tables.payment.status.' . $payment->status),
                6 => $type
            ];
        }

        $data = array(
            'draw' => $draw,
            'recordsTotal' => Payment::all()->count(),
            'recordsFiltered' => $no->count(),
            'data' => $data
        );

        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listPenalties()
    {
        $payments = Payment::whereDate('date', date('Y-m-15'))
            ->whereRaw('`id` IN (SELECT `payment_id` FROM `payment_penalties`)')
            ->get();

        return view('admin.payment.penalties', [
            'payments' => $payments
        ]);
    }

    /**
     * Receives payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function received(Request $request, Payment $payment)
    {
        if (!$payment->isPaid()) {
            $rules = $this->rules();

            $subscription = $payment->subscription;

            if ($subscription->isAuto() && $request->input('type') != 5 && !$subscription->isAutoPenalty()) {
                $next_payment = $subscription->nextMonthPayment();
                $new_price = $next_payment->price + 9.9;

                $data = [
                    'payment_id' => $next_payment->id,
                    'staff_id' => null,
                    'old_price' => $next_payment->price,
                    'new_price' => $new_price,
                    'description' => trans('response.system.auto_payment_penalty', ['price' => $new_price])
                ];

                MokaAutoPaymentDisable::create([
                    'subscription_id' => $subscription->id,
                    'payment_id' => $next_payment->id,
                    'old_price' => $next_payment->price,
                    'new_price' => $new_price
                ]);

                $next_payment->edit_price($data);
            }

            if (in_array($request->input('type'), [4, 5])) {
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
                    'string'
                ];
            }

            $moka = new Moka();

            if ($payment->mokaPayment) {
                $payment_detail = $moka->get_payment_detail_by_other_trx($payment->mokaPayment->trx_code);

                if (
                    $payment_detail->Data->PaymentDetail->PaymentStatus == 2 &&
                    $payment_detail->Data->PaymentDetail->TrxStatus == 1
                ) {
                    $payment->receive([
                        'type' => 4
                    ]);

                    return response()->json([
                        'success' => true,
                        'reload' => true
                    ]);
                }
            }

            MokaPayment::where('payment_id', $payment->id)->delete();

            $validated = $request->validate($rules);

            $date = Carbon::parse($payment->date);
            $month = Carbon::now()->format("m");

            if (env('APP_ENV') == 'production') {
                if ($request->input('type') == 4) {
                    $expire = Mutator::expire_date($validated["card"]["expire_date"]);

                    $card = [
                        'full_name' => $validated["card"]['full_name'],
                        'number' => $validated["card"]['number'],
                        'expire_month' => $expire[0],
                        'expire_year' => $expire[1],
                        'security_code' => $validated["card"]['security_code'],
                        'amount' => $payment->price
                    ];

                    $hash = [
                        'subscription_no' => $payment->subscription->subscription_no,
                        'payment_created_at' => $payment->created_at
                    ];

                    $response = $moka->pay(
                        $card,
                        route('payment.result', $payment),
                        $hash
                    );

                    if ($response->Data != null) {
                        MokaPayment::create([
                            'payment_id' => $payment->id,
                            'trx_code' => $moka->trx_code
                        ]);

                        MokaLog::create([
                            'payment_id' => $payment->id,
                            'ip' => $request->ip(),
                            'response' => ['init' => $response],
                            'trx_code' => $moka->trx_code
                        ]);

                        if ($request->input('auto_payment')) {
                            $this->define_auto_payment($payment, $validated);
                        }

                        return response()->json([
                            'success' => true,
                            'payment' => [
                                'frame' => $response->Data->Url
                            ]
                        ]);
                    }

                    MokaLog::create([
                        'payment_id' => $payment->id,
                        'ip' => $request->ip(),
                        'response' => $response,
                        'trx_code' => $moka->trx_code
                    ]);

                    return response()->json([
                        'error' => true,
                        'toastr' => [
                            'type' => 'error',
                            'title' => trans('response.title.error'),
                            'message' => trans('moka.' . str_replace('.', '_', $response->ResultCode))
                        ]
                    ]);
                } else if ($request->input('type') == 5) {
                    return $this->define_auto_payment($payment, $validated);
                }

                if ($payment->receive($validated)) {
                    return response()->json([
                        'success' => true,
                        'toastr' => [
                            'type' => 'success',
                            'title' => trans('response.title.success'),
                            'message' => trans('warnings.payment.successful')
                        ],
                        'reload' => true
                    ]);
                }

                return response()->json([
                    'error' => true,
                    'toastr' => [
                        'type' => 'error',
                        'title' => trans('response.title.error'),
                        'message' => trans('response.edit.error')
                    ]
                ]);
            }

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

    /**
     * Receives payment
     *
     * @param  \App\Models\Payment $payment
     * @param  array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function define_auto_payment(Payment $payment, array $data)
    {
        $expire = Mutator::expire_date($data["card"]["expire_date"]);

        $card = [
            'full_name' => $data["card"]['full_name'],
            'number' => $data["card"]['number'],
            'expire_month' => $expire[0],
            'expire_year' => $expire[1],
            'amount' => $payment->subscription->price
        ];

        $sale = MokaSale::where("subscription_id", $payment->subscription->id)->orderByDesc('id')->first();
        $moka = new Moka();

        if ($sale) {
            $moka->remove_card($sale->moka_card_token);

            $customer_id = $sale->moka_customer_id;
            $result = $moka->add_card(
                $customer_id,
                $card["full_name"],
                $card["number"],
                $expire[0],
                $expire[1]
            );

            if ($result->Data != null) {
                $card_token = $result->Data->CardList[0]->CardToken;

                $moka->update_sale(
                    $sale->moka_sale_id,
                    [
                        'card_token' => $card_token
                    ]
                );
            } else {
                $error = str_replace('.', '_', $result->ResultCode);
                $message = trans("moka.{$error}");

                return response()->json([
                    'error' => true,
                    'toastr' => [
                        'type' => 'error',
                        'title' => trans('response.title.error'),
                        'message' => $message
                    ]
                ]);
            }
        } else {
            $results = $moka->create_customer(
                [
                    'id' => md5($payment->subscription->subscription_no),
                    'first_name' => $payment->subscription->customer->first_name,
                    'last_name' => $payment->subscription->customer->last_name,
                    'telephone' => $payment->subscription->customer->telephone
                ],
                [
                    'full_name' => $card["full_name"],
                    'number' => $card["number"],
                    'expire_month' => $expire[0],
                    'expire_year' => $expire[1]
                ]
            );

            if ($results->Data != null) {
                $customer_id = $results->Data->DealerCustomer->DealerCustomerId;
                $card_token = $results->Data->CardList[0]->CardToken;
            } else {
                return response()->json([
                    'error' => true,
                    'toastr' => [
                        'type' => 'error',
                        'title' => trans('response.title.error'),
                        'message' => trans('warnings.payment.add_customer_failure')
                    ]
                ]);
            }
        }

        if ($customer_id && $card_token) {
            if ($sale) {
                $sale_id = $sale->moka_sale_id;
            } else {
                $dates = [
                    'start' => date('Ymd')
                ];

                if ($payment->subscription->end_date)
                    $dates["end"] = date('Ymd', strtotime($payment->subscription->end_date));
                else
                    $dates["end"] = "";

                $sale_response = $moka->add_sale(
                    [
                        'moka_id' => $customer_id,
                        'card_token' => $card_token
                    ],
                    [
                        'service_code' => $payment->subscription->service->model,
                        'amount' => $payment->subscription->price
                    ],
                    $dates
                );

                $sale_id = $sale_response->Data->DealerSaleId;
            }

            MokaSale::where("subscription_id", $payment->subscription->id)
                ->whereNull("disabled_at")
                ->update([
                    'disabled_at' => DB::raw('current_timestamp()')
                ]);
        }

        if (!$payment->subscription->isPreAuto()) {
            $next_payment = $payment->subscription->nextMonthPayment();

            $auto_discount = 9.9;
            $new_price = $next_payment->price - $auto_discount;
            if ($new_price <= 0)
                $new_price = $next_payment->price;

            $data = [
                'payment_id' => $next_payment->id,
                'staff_id' => null,
                'old_price' => $next_payment->price,
                'new_price' => $new_price,
                'description' => trans('response.system.auto_payment_discount', ['price' => $auto_discount])
            ];

            $next_payment->edit_price($data);
        }

        MokaSale::create([
            'subscription_id' => $payment->subscription->id,
            'moka_customer_id' => $customer_id,
            'moka_sale_id' => $sale_id,
            'moka_card_token' => $card_token
        ]);

        SentMessage::insert(
            [
                'customer_id' => $payment->subscription->customer->id,
                'message_id' => 15
            ]
        );

        return response()->json([
            'success' => true,
            'toastr' => [
                'type' => 'success',
                'title' => trans('response.title.success'),
                'message' => "Otomatik Ödeme"
            ],
            'reload' => true
        ]);
    }

    /**
     * Get plan payment info from Moka
     *
     * @param Request $request
     * @return void
     */
    public function payment_auto_result(Request $request)
    {
        echo 'OK';

        try {
            $data = [
                'method' => $request->method(),
                'ip' => $request->ip(),
                'values' => $request->all()
            ];

            $plan_id = (int)$request->input('DealerPaymentPlanId');
            $plan = MokaAutoPayment::where("moka_plan_id", $plan_id)->first();

            if ($plan) {
                $moka = new Moka();
                $payment_detail = $moka->get_payment_detail($request->input('DealerPaymentId'));

                if (
                    $plan->payment->status == 2 &&
                    $plan->payment->type != 5 &&
                    !$plan->isRefund()
                ) {
                    if (
                        isset($payment_detail->Data->PaymentDetail->OtherTrxCode) && 
                        !empty($payment_detail->Data->PaymentDetail->OtherTrxCode)
                    ) {
                        $refundType = 1;

                        $result = $moka->do_void(
                            $payment_detail->Data->PaymentDetail->OtherTrxCode
                        );

                        if ($result->Data == null) {
                            $refundType = 2;
                            $result = $moka->refund($payment_detail->Data->PaymentDetail->OtherTrxCode);
                        }

                        $success = false;
                        if ($result->Data != null && isset($result->Data->IsSuccessful) && (bool)$result->Data->IsSuccessful)
                            $success = true;

                        $plan->status = 5;
                        $plan->save();

                        MokaRefund::updateOrCreate(
                            [
                                'auto_payment_id' => $plan->id
                            ],
                            [
                                'payment_id' => $plan->payment->id,
                                'auto_payment_id' => $plan->id,
                                'price' => $plan->payment->price,
                                'status' => $success,
                                'type' => $refundType
                            ]
                        );
                    }
                } else {
                    if (
                        $payment_detail->Data->PaymentDetail->PaymentStatus == 2 &&
                        $payment_detail->Data->PaymentDetail->TrxStatus == 1
                    ) {
                        $plan->status = 1;
                        $plan->save();

                        $plan->payment->receive([
                            'type' => 5
                        ]);
                    } else {
                        $plan->status = 4;
                        $plan->save();
                    }
                }
            }

            DB::table('auto_results')->insert([
                'response' => json_encode($data),
            ]);
        } catch (Exception $e) {
            Telegram::send(
                "Test",
                "Payment Controller - Payment Auto Result Method : \n" . $e->getMessage()
            );
        }
    }

    /**
     * Gets Moka payment result
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Contracts\View\View
     */
    public function payment_result(Request $request, Payment $payment)
    {
        try {
            $success = false;

            $data = $request->all();

            $trx_code = $data["trxCode"];

            $moka_transaction = $payment->mokaPayment;
            $moka_transaction->moka_trx_code = $trx_code;
            $moka_transaction->save();

            $moka_log = MokaLog::where('trx_code', $moka_transaction->trx_code)->first();
            $moka_log->moka_trx_code = $trx_code;
            $init_response = $moka_log->response['init'];
            $moka_log->response = [
                'init' => $init_response,
                'result' => $data
            ];
            $moka_log->save();

            if ($moka_transaction) {
                $moka = new Moka();
                $result = $moka->get_payment_detail_by_other_trx($moka_transaction->trx_code);

                $moka_log->response = [
                    'init' => $init_response, 
                    'result' => $data,
                    'control' => $result
                ];
                $moka_log->save();

                if (
                    $result->Data->PaymentDetail->PaymentStatus == 2 &&
                    $result->Data->PaymentDetail->TrxStatus == 1
                ) {
                    $payment->receive([
                        'type' => 4
                    ]);

                    Telegram::send(
                        'RüzgarNETÖdeme',
                        trans('telegram.payment_received', [
                            'id_no' => $payment->subscription->customer->identification_number,
                            'full_name' => $payment->subscription->customer->full_name,
                            'price' => $payment->price,
                            'category' => $payment->subscription->service->category->name
                        ])
                    );

                    $success = true;
                }
            }

            return view('admin.response', ['response' => $success]);
        } catch (Exception $e) {
            Telegram::send(
                'Test',
                "Payment Controller - Payment Result Method : \n" . $e->getMessage()
            );

            return view('admin.response', ['response' => false]);
        }
    }

    /**
     * Create Moka payment instance for pre auth
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
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
            'string'
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
            route('payment.pre.auth.result', $moka_log),
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

    /**
     * Gets Moka payment result
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function payment_pre_auth_result(Request $request, MokaLog $moka_log)
    {
        // TODO Print result response
        $data = $request->all();

        if (isset($data["hashValue"])) {
            if (
                Moka::check_hash(
                    $data["hashValue"],
                    $moka_log->response["Data"]["CodeForHash"]
                )
            ) {
                return "Başarılı";
            }
            return "Başarısız";
        } else {
            return "Başarısız";
        }
    }

    /**
     * Update price
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function price(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:511'
        ]);

        $data = [
            'payment_id' => $payment->id,
            'staff_id' => $request->user()->staff_id,
            'old_price' => $payment->price,
            'new_price' => $validated['price'],
            'description' => $validated['description']
        ];

        if ($payment->paid_at == null && $payment->edit_price($data)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.edit.error')
            ]
        ]);
    }

    /**
     * Creates a new payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Subscription $subscription)
    {
        if ($request->input('is_lump_sum') != null) {
            $validated = $request->validate([
                'price' => 'required|numeric',
                'date' => 'required',
                'is_lump_sum' => 'required',
                'lump_sum_value' => 'required',
                'status' => 'required'
            ]);

            $iteration = 0;
            for ($iteration = 0; $iteration < $validated["lump_sum_value"]; $iteration++) {
                $temporary = [
                    'subscription_id' => $subscription->id,
                    'price' => $validated['price'],
                    'date' => date('Y-m-15', strtotime($validated['date'] . ' + ' . ($iteration + 1) . ' month')),
                    'status' => $validated["status"],
                    'type' => null
                ];

                Payment::create($temporary);
            }

            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.subscriptions')
            ]);
        } else {
            $validated = $request->validate([
                'price' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'required|string|max:511'
            ]);

            $validated['subscription_id'] = $subscription->id;
            $validated['staff_id'] = $request->user()->staff_id;

            if (PaymentCreate::createPayment($validated)) {
                return response()->json([
                    'success' => true,
                    'toastr' => [
                        'type' => 'success',
                        'title' => trans('response.title.success'),
                        'message' => trans('response.insert.success')
                    ],
                    'reload' => true
                ]);
            }

            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => trans('response.insert.error')
                ]
            ]);
        }
    }

    /**
     * Deletes a payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:511'
        ]);

        $error = null;
        $subscription = $payment->subscription;
        if ($subscription->approved_at == null)
            $error = trans('warnings.subscription.not_approved');

        if ($error) {
            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => $error
                ]
            ]);
        }

        $validated['staff_id'] = $request->user()->staff_id;

        if (PaymentDelete::deletePayment($payment, $validated)) {
            Telegram::send(
                "İptalİşlemler",
                trans(
                    "telegram.delete_payment",
                    [
                        "full_name" => $payment->subscription->customer->full_name,
                        "id_no" => $payment->subscription->customer->identification_number,
                        "payment_id" => $payment->id,
                        "description" => $validated["description"],
                        "username" => $request->user()->username
                    ]
                )
            );

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.delete.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.delete.error')
            ]
        ]);
    }

    /**
     * Creates a new payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:511'
        ]);

        $validated['payment_id'] = $payment->id;
        $validated['staff_id'] = $request->user()->staff_id;

        if (PaymentCancellation::cancel($payment, $validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.insert.error')
            ]
        ]);
    }

    /**
     * Rules for validation
     *
     * @return array
     */
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
