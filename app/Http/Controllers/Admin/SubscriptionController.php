<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Generator as Generator;
use App\Classes\Moka;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionCancellation;
use App\Models\Category;
use App\Models\SubscriptionChange;
use App\Models\Customer;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Reference;
use App\Models\SentMessage;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\SubscriptionFreeze;
use App\Models\SubscriptionPriceEdit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function index(int $status = null)
    {
        return view('admin.subscription.list', [
            'subscriptions' => Subscription::where('status', $status)->orderBy('id', 'DESC')->get(),
            'statuses' => trans('subscription.status'),
            'services' => Service::select('id', 'name')->get(),
            'status' => $status
        ]);
    }

    /**
     * Get a listing of the resource.
     *
     * @return array
     */
    public function list(Request $request)
    {
        $offset = $request->input('start');
        $limit = $request->input('length');
        $draw = $request->input('draw');
        $status = $request->input('columns.3.search.value');

        if ($status == "") {
            $subscriptions = Subscription::offset($offset)->limit($limit)->orderBy('id', 'desc')->get();
            $no = Subscription::orderBy('id', 'desc')->get();
        } else {
            $subscriptions = Subscription::offset($offset)->where("status", $status)->limit($limit)->orderBy('id', 'desc')->get();
            $no = Subscription::where("status", $status)->orderBy('id', 'desc')->get();
        }

        $data = [];
        foreach ($subscriptions as $subscription) {
            $html = '<div class="buttons">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button"
                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    ' . trans('fields.actions') . '
                </button>
                <div class="dropdown-menu dropdown-menu-right"
                    aria-labelledby="dropdownMenuButton">';

            if ($subscription->status == 0) {
                $html .= ' <a href="' . route('admin.subscription.edit', $subscription) . '"
                        class="dropdown-item">
                        <i class="dropdown-icon fas fa-edit"></i>
                        ' . trans('titles.edit') . '
                    </a>

                    <a target="_blank" class="dropdown-item"
                        href="' . route('admin.subscription.contract', $subscription) . '">
                        <i class="dropdown-icon fas fa-file-contract"></i>
                        ' . trans('fields.contract_preview') . '
                    </a>

                    <button type="button"
                        class="dropdown-item confirm-modal-btn"
                        data-action="' . relative_route('admin.subscription.approve.post', $subscription) . '"
                        data-modal="#approveSubscription">
                        <i class="dropdown-icon fas fa-check"></i>
                        ' . trans('titles.approve') . '
                    </button>

                    <button type="button"
                        class="dropdown-item confirm-modal-btn"
                        data-action="' . relative_route('admin.subscription.delete', $subscription) . '"
                        data-modal="#delete">
                        <i class="dropdown-icon fas fa-trash"></i>
                        ' . trans('titles.delete') . '
                    </button>';
            }
            if ($subscription->approved_at) {
                $html .= '<a href="' . route('admin.subscription.payments', $subscription) . '"
                    class="dropdown-item">
                    <i class="dropdown-icon fas fa-file-invoice"></i>
                    ' . trans('tables.payment.title') . '
                </a>';
            }

            if (!$subscription->isChangedNew()) {
                $html .= '<a target="_blank" class="dropdown-item"
                    href="/contracts/' . md5($subscription->subscription_no) . '.pdf">
                    <i class="dropdown-icon fas fa-file-contract"></i>
                    ' . trans('fields.contract') . '
                </a>';
            }

            $html .= '</div>
                </div>
            </div>';

            $status_html = '<div class="buttons">';
            if ($subscription->isCanceled()) {
                $status_html .= '<button type="button" class="btn btn-danger btn-sm"
                data-toggle="popover" data-html="true"
                data-content="<b>Tarih:</b>' . convert_date($subscription->cancellation->created_at, 'large') . '<br>
                <b>Personel</b>: ' . $subscription->cancellation->staff->full_name . '<br>
                <b>Sebep</b>: ' . $subscription->cancellation->description . '">
                ' . trans('titles.cancel') . '
                </button>';
            }

            if ($subscription->isChanged()) {
                $status_html .= '<a class="btn btn-info btn-sm" title="' . trans('fields.changed_service') . '"
                    href="' . route('admin.subscription.payments', $subscription->getChanged()) . '">
                    ' . $subscription->getChanged()->service->name . '
                </a>';
            }

            if ($subscription->isFreezed()) {
                $status_html .= '<button type="button" class="btn btn-warning btn-sm"
                    data-toggle="popover" data-html="true"
                    data-content="<b>Tarih:</b>' . convert_date($subscription->freeze->created_at, 'large') . '<br>
                    <b>Personel</b>: {{ $subscription->freeze->staff->full_name }} <br>
                    <b>Sebep</b>: {{ $subscription->freeze->description }}">
                    ' . trans('titles.freezed') . '
                </button>';
            }

            if (!$subscription->approved_at) {
                $status_html .= '<button type="button" class="btn btn-secondary">
                    ' . trans('titles.unapproved') . '
                </button>';
            }

            if ($subscription->isChangedNew()) {
                $status_html .= '<button type="button" class="btn btn-info">
                    ' . trans('fields.changed_service') . '
                </button>';
            }

            $status_html .= '</div>';

            $data[] = [
                0 => $subscription->id,
                1 => '<a href="' . route('admin.customer.show', $subscription->customer_id) . '">' . $subscription->customer->full_name . '</a>',
                2 => $subscription->service->name,
                3 => $status_html,
                4 => print_money($subscription->price),
                5 => convert_date($subscription->start_date, 'medium') . "-" . convert_date($subscription->end_date, 'medium'),
                6 => $subscription->staff->full_name ?? "-",
                7 => $html
            ];
        }

        $data = array(
            'draw' => $draw,
            'recordsTotal' => Subscription::all()->count(),
            'recordsFiltered' => $no->count(),
            'data' => $data
        );

        return $data;
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
        $devices = $subscription->getOption("devices") ?? null;
        if (!$subscription->approved_at)
            $subscription->generatePayments();

        $pdf = Pdf::loadView("pdf.contract.{$subscription->service->category->contractType->view}", [
            'subscription' => $subscription,
            'barcode' => Generator::barcode($subscription->subscription_no),
            'devices' => $devices
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

        if ($validated["commitment"] == 0 || $validated["commitment"] == 12) {
            $validated["price"] += 10;
        }

        if ($validated['commitment'] > 0) {
            $date = new DateTime($validated['start_date']);
            $date->modify("+{$validated['commitment']} month");
            $validated['end_date'] = $date->format('Y-m-d');
        } else {
            $validated['end_date'] = null;
        }

        $reference_id = null;
        if (isset($validated['reference_id']) && $validated['reference_id'] != null) {
            $reference_id = $validated['reference_id'];
            unset($validated['reference_id']);
        }

        $validated["subscription_no"] = Generator::subscriptionNo();

        if ($subscription = Subscription::create($validated)) {
            if ($reference_id) {
                $data = [
                    'reference_id' => $reference_id,
                    'referenced_id' => $subscription->id
                ];

                Reference::create($data);
            }

            Telegram::send(
                'AboneTamamlanan',
                trans(
                    'telegram.add_subscription',
                    [
                        'full_name' => $subscription->customer->full_name,
                        'id_no' => $subscription->customer->identification_number,
                        'username' => $subscription->staff->full_name,
                        'customer_staff' => $subscription->customer->staff->full_name
                    ]
                )
            );

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
     * Cancel auto payment
     *
     * @param \App\Models\Subscription $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel_auto_payment(Subscription $subscription)
    {
        $auto_payment = $subscription->getAuto();
        $auto_payment->disabled_at = DB::raw('current_timestamp()');

        $moka = new Moka();
        $moka->remove_card($auto_payment->moka_card_token);

        if ($auto_payment->save()) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'reload' => true
            ]);
        }

        return response()->json([
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
    public function edit(Subscription $subscription)
    {
        $devices = $subscription->getOption("devices") ?? [];
        $data = array_merge(
            $this->viewData(),
            [
                'subscription' => $subscription,
                'devices' => $devices
            ]
        );

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

        if (
            !in_array($subscription->commitment, [0, 12]) &&
            in_array($validated["commitment"], [0, 12])
        ) {
            $validated["price"] += 10;
        } else if (
            in_array($subscription->commitment, [0, 12]) &&
            !in_array($validated["commitment"], [0, 12])
        ) {
            $validated["price"] -= 10;
        }

        $reference_id = null;
        if (isset($validated['reference_id']) && $validated['reference_id'] != null) {
            $reference_id = $validated['reference_id'];
            unset($validated['reference_id']);
        }

        if ($subscription->update($validated)) {
            if ($reference_id != null) {
                Reference::where('referenced_id', $subscription->id)->delete();

                $data = [
                    'reference_id' => $reference_id,
                    'referenced_id' => $subscription->id
                ];

                Reference::create($data);
            }

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

        if ($subscription->delete()) {
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
     * Approve subscription
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Subscription $subscription)
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

        if ($subscription->approve_subscription()) {
            SentMessage::insert([
                [
                    'customer_id' => $subscription->customer->id,
                    'message_id' => 10
                ],
                [
                    'customer_id' => $subscription->customer->id,
                    'message_id' => 11
                ]
            ]);

            SentMessage::insert([
                [
                    'customer_id' => $subscription->customer->id,
                    'message_id' => 12,
                    'delivery_date' => date('Y-m-d 11:00', strtotime(' +1 day '))
                ],
                [
                    'customer_id' => $subscription->customer->id,
                    'message_id' => 13,
                    'delivery_date' => date('Y-m-d 11:00', strtotime(' +1 week '))
                ]
            ]);

            $pdf = Pdf::loadView("pdf.contract.{$subscription->service->category->contractType->view}", [
                'subscription' => $subscription,
                'device_brand' => 'Huawei',
                'device_model' => 'HGS',
                'barcode' => Generator::barcode($subscription->subscription_no),
                'devices' => $subscription->getOption("devices") ?? []
            ]);
            $path = "contracts/" . md5($subscription->subscription_no) . ".pdf";
            if (!file_exists(public_path('contracts'))) {
                mkdir(public_path('contracts'), 0755, true);
            }
            $pdf->save($path);

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.approve.subscription'),
                    'message' => trans('response.approve.subscription.success')
                ],
                'reload' => true
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
                'reload' => true
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

        if (SubscriptionPriceEdit::edit_price($subscription, $data)) {
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
                'redirect' => relative_route('admin.customer.show', $subscription->customer)
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
            Telegram::send(
                'İptalİşlemler',
                trans(
                    "telegram.cancel_subscription",
                    [
                        'full_name' => $subscription->customer->full_name,
                        'id_no' => $subscription->customer->identification_number,
                        'description' => $validated["description"],
                        'username' => $request->user()->username
                    ]
                )
            );

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
            Telegram::send(
                'Test',
                trans('telegram.add_freeze', [
                    'full_name' => $subscription->customer->full_name,
                    'subsciption' => $subscription->service->name,
                    'username' => $request->user()->staff->full_name
                ])
            );

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
            Telegram::send(
                'Test',
                trans('telegram.unfreeze', [
                    'full_name' => $subscription->customer->full_name,
                    'subsciption' => $subscription->service->name,
                    'username' => $request->user()->staff->full_name
                ])
            );

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
            'customers' => Customer::where('type', '<>', 1)->orderBy('id', 'DESC')->get(),
            'subscriptions' => Subscription::where('status', 1)->orderBy('id', 'DESC')->get(),
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
                    return $query->where('type', '<>', 1);
                })
            ],
            'start_date' => 'required|date',
            'price' => 'required|numeric',
            'options.address' => 'nullable|string|max:255',
            'reference_id' => [
                'nullable',
                Rule::exists('customers', 'reference_code')->where(function ($query) {
                    return $query;
                })
            ],
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
                    if (request()->input('options.modem') && in_array(request()->input('options.modem'), [2, 3, 5, 6])) {
                        $optionRules['options.devices.modem_brand.*'] = [
                            'nullable',
                            'string',
                            'max:255'
                        ];
                        $optionRules['options.devices.modem_serial.*'] = [
                            'nullable',
                            'string',
                            'max:255'
                        ];
                        $optionRules['options.devices.modem_model.*'] = [
                            'nullable',
                            'string',
                            'max:255'
                        ];
                    }
                } else if ($key == 'pre_payment') {
                    $optionRules['options.pre_payment'] = [
                        'nullable',
                        'boolean'
                    ];
                } else if ($key == 'modem_price' && request()->input("options.modem") != 1) {
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

                    $optionRules[$option] = [
                        'required',
                        Rule::in($value)
                    ];
                }
            }
        }

        return $optionRules;
    }
}
