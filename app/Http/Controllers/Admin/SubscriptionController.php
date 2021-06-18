<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Generator as Generator;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionCancellation;
use App\Models\Category;
use App\Models\SubscriptionChange;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\SubscriptionFreeze;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.subscription.list', ['subscriptions' => Subscription::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.subscription.add', $this->viewData());
    }

    /**
     * Undocumented function
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function preview(Subscription $subscription)
    {
        $pdf = Pdf::loadView("pdf.contract.{$subscription->service->category->contractType->view}", [
            'subscription' => $subscription,
            'barcode' => Generator::barcode("0000000000")
        ]);
        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array_merge($this->rules(), $this->optionRules());
        $validated = $request->validate($rules);

        $validated['staff_id'] = $request->user()->staff_id;

        if ($validated['commitment'] > 0) {
            $date = new DateTime($validated['start_date']);
            $date->modify("+{$validated['commitment']} month");
            $validated['end_date'] = $date->format('Y-m-d');
        } else {
            $validated['end_date'] = null;
        }

        if (Subscription::create($validated)) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.subscriptions')
            ]);
        }

        return response()->json([
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.insert.error')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Subscription $subscription)
    {
        $data = array_merge($this->viewData(), ['subscription' => $subscription]);

        return view('admin.subscription.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Subscription $subscription)
    {
        if ($subscription->approved_at) {
            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => trans('warnings.approved.subscription')
                ]
            ]);
        }

        $rules = array_merge($this->rules(), $this->optionRules());
        $validated = $request->validate($rules);

        if ($validated['commitment'] > 0) {
            $date = new DateTime($validated['start_date']);
            $date->modify("+{$validated['commitment']} month");
            $validated['end_date'] = $date->format('Y-m-d');
        } else {
            $validated['end_date'] = null;
        }

        if ($subscription->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.subscriptions')
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
     * @param Request $request
     * @param Subscription $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_payment(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'price' => 'required|numeric',
            'date' => 'required',
            'status' => 'required',
            'type' => 'required'
        ]);

        $validated['date'] = date('Y-m-15', strtotime($validated['date']));
        $validated["subscription_id"] = $subscription->id;

        if (Payment::create($validated)) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.subscriptions')
            ]);
        }

        return response()->json([
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.insert.error')
            ]
        ]);
    }

    /**
     * Deletes a payment
     *
     * @param Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_payment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'description' => 'required'
        ]);

        $id = $payment->id;
        if ($payment->delete()) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.delete.success')
                ],
                'deleted' => $id
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Subscription $subscription)
    {
        if ($subscription->approved_at) {
            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => trans('warnings.approved.subscription')
                ]
            ]);
        }

        $id = $subscription->id;
        if ($subscription->delete()) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.delete.success')
                ],
                'deleted' => $id
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
     * Approve subscription
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Subscription $subscription)
    {
        if ($subscription) {
            if ($subscription->approved_at) {
                return response()->json([
                    'error' => true,
                    'toastr' => [
                        'type' => 'error',
                        'title' => trans('response.title.error'),
                        'message' => trans('warnings.approved.subscription')
                    ]
                ]);
            }

            if ($subscription->approve_subscription()) {
                $pdf = Pdf::loadView("pdf.contract.{$subscription->service->category->contractType->view}", [
                    'subscription' => $subscription,
                    'device_brand' => 'Huawei',
                    'device_model' => 'HGS',
                    'barcode' => Generator::barcode($subscription->subscription_no)
                ]);
                $path = "contracts/" . md5($subscription->subscription_no) . ".pdf";
                if (!file_exists(public_path('contracts'))) {
                    mkdir(public_path('contracts'), 0755, true);
                }
                $pdf->save($path);

                //Telegram::send('1046241971', 'sub approve');

                return response()->json([
                    'success' => true,
                    'toastr' => [
                        'type' => 'success',
                        'title' => trans('response.title.approve.subscription'),
                        'message' => trans('response.approve.subscription.success')
                    ],
                    'approve' => [
                        'id' => $subscription->id
                    ]
                ]);
            }

            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.approve.subscription'),
                    'message' => trans('response.approve.subscription.error')
                ]
            ]);
        }
    }

    /**
     * Approve subscription
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function unApprove(Subscription $subscription)
    {
        if ($subscription->unapprove_subscription()) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.approve.subscription'),
                    'message' => trans('response.approve.subscription.success')
                ],
                'approve' => [
                    'id' => $subscription->id,
                    'unapprove' => true
                ]
            ]);
        }
    }

    /**
     * Show subscription payments
     *
     * @param Subscription $subscription
     * @return \Illuminate\Contracts\View\View
     */
    public function payments(Subscription $subscription)
    {
        $data = [
            'paymentTypes' => Payment::getTypes(),
            'subscription' => $subscription,
            'statuses' => trans('tables.payment.status'),
            'types' => trans('tables.payment.types')
        ];
        return view('admin.subscription.payment', $data);
    }

    /**
     * Update price
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function price(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:511'
        ]);

        $data = [
            'subscription_id' => $subscription->id,
            'staff_id' => $request->user()->staff_id,
            'old_price' => $subscription->price,
            'new_price' => $validated['price'],
            'description' => $validated['description']
        ];

        if ($subscription->edit_price($data)) {
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Contracts\View\View
     */
    public function change(Subscription $subscription)
    {
        $data = [
            'subscription' => $subscription,
            'services' => Service::where('status', 1)->get()
        ];

        return view('admin.subscription.change', $data);
    }

    /**
     * Change service
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function upgrade(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'service_id' => [
                'required',
                Rule::exists('services', 'id')->where(function ($query) {
                    return $query->where('status', 1);
                })
            ],
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'payment' => 'required|numeric|min:0',
        ]);

        $error = null;

        if ($subscription->approved_at == null)
            $error = trans('warnings.subscription.not_approved');
        if ($subscription->isCanceled())
            $error = trans('warnings.subscription.already_canceled');
        if ($subscription->isChanged())
            $error = trans('warnings.subscription.changed');
        if ($subscription->isEnded())
            $error = trans('warnings.subscription.ended');
        if ($subscription->isFreezed())
            $error = trans('warnings.subscription.freezed');
        if ($validated['service_id'] == $subscription->service_id)
            $error = trans('warnings.subscription.cant_change_same_service');

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

        if (SubscriptionChange::change($subscription, $validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.subscription.change.success')
                ],
                'redirect' => relative_route('admin.subscriptions')
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.subscription.change.error')
            ]
        ]);
    }

    /**
     * Cancel subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:511',
        ]);

        $error = null;

        if ($subscription->approved_at == null)
            $error = trans('warnings.subscription.not_approved');
        if ($subscription->isCanceled())
            $error = trans('warnings.subscription.already_canceled');
        if ($subscription->isChanged())
            $error = trans('warnings.subscription.changed');
        if ($subscription->isEnded())
            $error = trans('warnings.subscription.ended');

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
        $validated['subscription_id'] = $subscription->id;

        if (SubscriptionCancellation::cancel($subscription, $validated)) {
            // TODO Change group for production
            // FIXME Named groups
            Telegram::send('1046241971', 'cancel');

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.subscription.cancel.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.subscription.cancel.error')
            ]
        ]);
    }

    /**
     * Freeze subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function freeze(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:511',
        ]);

        $error = null;

        if ($subscription->approved_at == null)
            $error = trans('warnings.subscription.not_approved');
        if ($subscription->isCanceled())
            $error = trans('warnings.subscription.canceled');
        if ($subscription->isChanged())
            $error = trans('warnings.subscription.changed');
        if ($subscription->isEnded())
            $error = trans('warnings.subscription.ended');
        if ($subscription->isFreezed())
            $error = trans('warnings.subscription.already_freezed');

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
        $validated['subscription_id'] = $subscription->id;

        if (SubscriptionFreeze::freeze($subscription, $validated)) {
            // TODO Change group for production
            // FIXME Named groups
            Telegram::send('1046241971', 'freeze');

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.subscription.cancel.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.subscription.cancel.error')
            ]
        ]);
    }

    /**
     * Unfreeze subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFreeze(Request $request, Subscription $subscription)
    {
        $error = null;

        if ($subscription->approved_at == null)
            $error = trans('warnings.subscription.not_approved');
        if ($subscription->isCanceled())
            $error = trans('warnings.subscription.canceled');
        if ($subscription->isChanged())
            $error = trans('warnings.subscription.changed');
        if ($subscription->isEnded())
            $error = trans('warnings.subscription.ended');
        if (!$subscription->isFreezed())
            $error = trans('warnings.subscription.not_freezed');

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

        $staff_id = $request->user()->staff_id;

        if (SubscriptionFreeze::unFreeze($subscription, $staff_id)) {
            // TODO Change group for production
            // FIXME Named groups
            Telegram::send('1046241971', 'unfreeze');

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.subscription.cancel.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.subscription.cancel.error')
            ]
        ]);
    }

    /**
     * Data for view
     *
     * @return array
     */
    private function viewData()
    {
        $categories = Category::where('status', 1)->where('type', 2)->get();

        $option_fields = [];
        foreach ($categories as $category) {
            $option_fields[$category->key] = $category->option_fields;
        }

        $services = Service::where('status', 1)->get();

        $service_props = [];
        foreach ($services as $service) {
            $service_props[$service->id] = [
                'price' => $service->price,
                'category' => $service->category->key
            ];
        }

        $data = [
            'services' => Service::where('status', 1)->orderByRaw('name * 1')->get(),
            'customers' => Customer::where('type', 2)->orderBy('id', 'DESC')->get(),
            'option_fields' => $option_fields,
            'service_props' => $service_props
        ];

        return $data;
    }

    /**
     * Rules for validation
     *
     * @return array
     */
    private function rules()
    {
        return [
            'service_id' => [
                'required',
                Rule::exists('services', 'id')->where(function ($query) {
                    return $query->where('status', 1);
                })
            ],
            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where(function ($query) {
                    return $query->where('type', 2);
                })
            ],
            'start_date' => 'required|date',
            'price' => 'required|numeric',
            'options.address' => 'nullable|string|max:255'
        ];
    }

    /**
     * Rules for service's options
     *
     * @return array
     */
    private function optionRules()
    {
        $optionRules = [];

        if (request()->input('service_id')) {
            $service = Service::find(request()->input('service_id'));
            $options = $service->category->options;

            if (request()->input('options.modem') && in_array(request()->input('options.modem'), [2, 3, 4])) {
                $optionRules['bbk_code'] = [
                    'required',
                    'string',
                    'max:255'
                ];
            } else {
                $optionRules['bbk_code'] = [
                    'nullable',
                    'string',
                    'max:255'
                ];
            }

            foreach ($options as $key => $value) {
                if ($key == 'modem_serial') {
                    if (request()->input('options.modem') && in_array(request()->input('options.modem'), [2, 3, 5])) {
                        $optionRules['options.modem_serial'] = [
                            'required',
                            'string',
                            'max:255'
                        ];
                    }
                } else if ($key == 'pre_payment') {
                    $optionRules['options.pre_payment'] = [
                        'nullable',
                        'boolean'
                    ];
                } else if ($key == 'modem_price' && request()->input("options.modem") == 5) {
                    $optionRules["options.modem_price"] = [
                        'required',
                        'numeric'
                    ];
                } else if ($key == 'modem_model') {
                    if (in_array(request()->input("options.modem"), [2, 3, 5])) {
                        $values = json_decode(setting("service.modems"), true);
                        $data = [];
                        foreach ($values as $item) {
                            $data[] = $item["value"];
                        }
                        $optionRules["options.modem_model"] = [
                            'required',
                            Rule::in($data)
                        ];
                    }
                } else if (is_array($value)) {
                    $option = (string)Str::of($key)->singular();
                    if ($option != 'commitment') {
                        $option = "options.{$option}";
                    }

                    if ($key == 'modem_payments') {
                        if (!in_array(request()->input("options.modem"), [1, 4, 5])) {
                            $optionRules[$option] = [
                                'required',
                                Rule::in($value)
                            ];
                        }
                    } else {
                        $optionRules[$option] = [
                            'required',
                            Rule::in($value)
                        ];
                    }
                }
            }
        }

        return $optionRules;
    }
}
