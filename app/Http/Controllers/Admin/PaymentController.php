<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Moka;
use App\Classes\Mutator;
use App\Http\Controllers\Controller;
use App\Models\MokaAutoPayment;
use App\Models\MokaLog;
use App\Models\MokaPayment;
use App\Models\MokaSale;
use App\Models\Payment;
use App\Models\PaymentCancellation;
use App\Models\PaymentCreate;
use App\Models\PaymentDelete;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
        }

        if ($request->input('type') == 4) {
            $rules["card.security_code"] = [
                'required',
                'numeric',
                'between:100,999'
            ];
        }

        $validated = $request->validate($rules);

        $date = Carbon::parse($payment->date);
        $month = Carbon::now()->format("m");

        // TODO remove env conditions for product
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

                $expire = Mutator::expire_date($validated["card"]["expire_date"]);

                $card = [
                    'full_name' => $validated["card"]['full_name'],
                    'number' => $validated["card"]['number'],
                    'expire_month' => $expire[0],
                    'expire_year' => $expire[1],
                    'amount' => $payment->price
                ];
                if (true) {
                    $sale = MokaSale::where("customer_id", $payment->subscription->customer->id)->first();

                    $moka = new Moka();
                    if ($sale) {
                        $customer_id = $sale->moka_customer_id;
                        $result = $moka->add_card(
                            $customer_id,
                            $card["full_name"],
                            $card["number"],
                            $expire[0],
                            $expire[1]
                        );

                        if($result->Data != null)
                        {
                            $card_token = $result->Data->CardList[0]->CardToken;

                            $moka->update_sale(
                                $sale->moka_sale_id,
                                $card_token
                            );
                        }
                        else
                        {
                            return response()->json([
                                'error' => true,
                                'toastr' => [
                                    'type' => 'error',
                                    'title' => trans('response.title.error'),
                                    'message' => trans('warnings.payment.add_card_failure')
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
                        }
                        else
                        {
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

                    if($customer_id && $card_token)
                    {
                        $dates = [
                            'start' => date('Ymd', strtotime($payment->subscription->created_at))
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

                        MokaSale::create([
                            'customer_id' => $payment->subscription->customer->id,
                            'subscription_id' => $payment->subscription->id,
                            'moka_customer_id' => $customer_id,
                            'moka_sale_id' => $sale_response->Data->DealerSaleId,
                            'moka_card_token' => $card_token
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'toastr' => [
                            'type' => 'error',
                            'title' => trans('response.title.error'),
                            'message' => trans('warnings.payment.is_already_auto')
                        ]
                    ]);
                }

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

    /**
     * Send sale plan to moka for test
     *
     * @param Payment $payment
     * @return void
     */
    public function send_auto(Payment $payment)
    {
        $auto = $payment->subscription->get_auto();
        $moka = new Moka();
        $result = $moka->add_payment_plan(
            $auto->moka_sale_id,
            date('Ymd', strtotime(' + 1 day')),
            0.01
        );

        if (isset($result->Data->DealerPaymentPlanId)) {
            MokaAutoPayment::create([
                'sale_id' => $auto->id,
                'payment_id' => $payment->id,
                'moka_plan_id' => $result->Data->DealerPaymentPlanId
            ]);
        }
    }

    /**
     * Get plan payment info from Moka
     *
     * @param Request $request
     * @return void
     */
    public function payment_auto_result(Request $request)
    {
        $data = [
            "method" => $request->method(),
            "ip" => $request->ip(),
            "values" => $request->all()
        ];
        DB::table('auto_results')->insert([
            'response' => json_encode($data),
        ]);

        return "OK";
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
        // TODO Print result response
        $data = $request->all();

        $moka_transaction = $payment->mokaPayment;
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

        if (isset($data["hashValue"])) {
            if (
                isset($moka_transaction) &&
                Moka::check_hash(
                    $data["hashValue"],
                    $moka_transaction->response["Data"]["CodeForHash"]
                )
            ) {
                $payment->receive([
                    'type' => 4
                ]);

                $moka_transaction->moka_trx_code = $data["trxCode"];
                $moka_transaction->save();

                return "Başarılı";
            }
            $payment->mokaPayment->delete();
            return "Başarısız";
        } else {
            $payment->mokaPayment->delete();
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
                'status' => 'required',
                'type' => 'required',
                'is_lump_sum' => 'required',
                'lump_sum_value' => 'required'
            ]);


            $iteration = 0;
            for ($iteration = 0; $iteration < $validated["lump_sum_value"]; $iteration++) {
                $temporary = [
                    'subscription_id' => $subscription->id,
                    'price' => $validated['price'],
                    'date' => date('Y-m-15', strtotime($validated['date'] . ' + ' . ($iteration + 1) . ' month')),
                    'status' => $validated['status'],
                    'type' => $validated['type']
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
