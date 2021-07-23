<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Moka;
use App\Classes\Mutator;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MokaAutoPayment;
use App\Models\MokaLog;
use App\Models\MokaPayment;
use App\Models\MokaRefund;
use App\Models\MokaSale;
use App\Models\Payment;
use App\Models\PaymentCancellation;
use App\Models\PaymentCreate;
use App\Models\PaymentDelete;
use App\Models\SentMessage;
use App\Models\Service;
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
                'services' => Service::all()
            ]
        );
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
        $service_id = $request->input('columns.2.search.value');

        $payments = Payment::orderBy('payments.id', 'desc');
        $no = Payment::orderBy('payments.id', 'desc');

        if ($service_id != "") {
            $payments = $payments
                ->join('subscriptions', 'subscriptions.id', 'payments.subscription_id')
                ->whereRaw("subscriptions.service_id = " . $service_id);
            $no = $no
                ->join('subscriptions', 'subscriptions.id', 'payments.subscription_id')
                ->whereRaw("subscriptions.service_id = " . $service_id);
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
     * Receives payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function received(Request $request, Payment $payment)
    {
        if (!$payment->isPaid()) {
            $rules = $this->rules();

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

            $validated = $request->validate($rules);

            $date = Carbon::parse($payment->date);
            $month = Carbon::now()->format("m");

            MokaPayment::where('payment_id', $payment->id)->delete();

            if (env('APP_ENV') == 'local' || $date->format('m') == $month) {
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

                    $moka = new Moka();
                    $response = $moka->pay(
                        $card,
                        route('payment.result', $payment),
                        $hash
                    );

                    if ($response->Data != null) {
                        MokaPayment::create([
                            'payment_id' => $payment->id,
                            'response' => $response,
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
                            'message' => $response->ResultCode
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
            'amount' => $payment->price
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

            MokaSale::create([
                'subscription_id' => $payment->subscription->id,
                'moka_customer_id' => $customer_id,
                'moka_sale_id' => $sale_id,
                'moka_card_token' => $card_token
            ]);
        }

        if (!$payment->subscription->isPreAuto()) {
            $next_payment = $payment->subscription->nextPayment();

            $auto_discount = setting('auto.define.price', 9.9);
            if (!is_numeric($auto_discount)) {
                $auto_discount = 9.9;
            }

            $data = [
                'payment_id' => $payment->id,
                'staff_id' => null,
                'old_price' => $next_payment->price,
                'new_price' => $next_payment->price - $auto_discount,
                'description' => trans('response.system.auto_payment_discount', ['price' => $auto_discount])
            ];

            if (!$payment->isPaid()) {
                $payment->edit_price($data);
            }
        }

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
                        !empty($payment_detail->Data->PaymentDetail->OtherTrxCode) &&
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
     * @return void
     */
    public function payment_result(Request $request, Payment $payment)
    {
        try {
            $data = $request->all();

            $trx_code = $data["trxCode"];

            $moka_transaction = $payment->mokaPayment;
            $moka_transaction->moka_trx_code = $trx_code;
            $moka_transaction->save();

            if ($moka_transaction) {
                MokaLog::insert([
                    [
                        'payment_id' => $payment->id,
                        'ip' => $request->ip(),
                        'response' => json_encode($moka_transaction->response),
                        'trx_code' => $moka_transaction->trx_code,
                        'type' => 6
                    ], [
                        'payment_id' => $payment->id,
                        'ip' => $request->ip(),
                        'response' => json_encode($data),
                        'trx_code' => $moka_transaction->trx_code,
                        'type' => 6
                    ]
                ]);

                if (
                    isset($data["hashValue"]) &&
                    isset($moka_transaction) &&
                    Moka::check_hash(
                        $data["hashValue"],
                        $moka_transaction->response["Data"]["CodeForHash"]
                    )
                ) {
                    $payment->receive([
                        'type' => 4
                    ]);

                    Telegram::send(
                        "RüzgarNETÖdeme",
                        "Başarılı bir ödeme gerçekleştirildi. \nT.C. Kimlik Numarası : " . $payment->subscription->customer->identification_number . " \nAdı Soyadı : " . $payment->subscription->customer->full_name . " \nTutar : " . $payment->price . " \nMarka : " . $payment->subscription->service->category->name
                    );

                    return view('admin.response', ["response" => 1]);
                } else {
                    $moka = new Moka();
                    $result = $moka->get_payment_detail_by_other_trx($moka_transaction->trx_code);

                    if (
                        $result->Data->PaymentDetail->PaymentStatus == 2 &&
                        $result->Data->PaymentDetail->TrxStatus == 1
                    ) {
                        $payment->receive([
                            'type' => 4
                        ]);

                        Telegram::send(
                            "RüzgarNETÖdeme",
                            "Başarılı bir ödeme gerçekleştirildi. \nT.C. Kimlik Numarası : " . $payment->subscription->customer->identification_number . " \nAdı Soyadı : " . $payment->subscription->customer->full_name . " \nTutar : " . $payment->price . " \nMarka : " . $payment->subscription->service->category->name
                        );

                        return view('admin.response', ["response" => 1]);
                    } else {
                        MokaPayment::where('payment_id', $payment->id)->delete();
                        return view('admin.response', ["response" => 0]);
                    }
                }
            }

            MokaPayment::where('payment_id', $payment->id)->delete();
            return view('admin.response', ["response" => 0]);
        } catch (Exception $e) {
            Telegram::send(
                "Test",
                $e->getMessage()
            );
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

        $validated['subscription_id'] = $payment->id;
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
