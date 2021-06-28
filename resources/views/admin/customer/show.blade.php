@extends('admin.layout.main')

@section('title', meta_title('tables.customer.info'))

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    <img alt="image" src="/assets/admin/img/avatar/avatar-1.png"
                        class="rounded-circle profile-widget-picture">
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label" title="@lang('fields.customer_no')">
                                #{{ $customer->customer_no }}</div>
                            <div class="profile-widget-item-value" title="@lang('fields.name_surname')">
                                {{ $customer->full_name }}</div>
                        </div>
                    </div>
                </div>
                <div class="profile-widget-description">
                    <ul class="fa-ul mb-0">
                        <li title="@lang('fields.reference_code')">
                            <span class="fa-li"><i class="fas fa-address-book"></i></span>
                            {{ $customer->reference_code }}
                        </li>
                        <li title="@lang('fields.identification_number')">
                            <span class="fa-li"><i class="fas fa-id-card"></i></span>
                            {{ $customer->identification_secret }}
                        </li>
                        <li title="@lang('fields.gender')">
                            <span class="fa-li"><i class="fas fa-venus-mars"></i></span>
                            @lang("fields.genders.{$customer->customerInfo->gender}")
                        </li>
                        <li title="@lang('fields.telephone')">
                            <span class="fa-li"><i class="fas fa-mobile-alt"></i></span>
                            {{ $customer->telephone_print }}
                        </li>
                        <li title="@lang('fields.email')">
                            <span class="fa-li"><i class="fas fa-at"></i></span>
                            {{ $customer->email }}
                        </li>
                        <li title="@lang('fields.secondary_telephone')">
                            <span class="fa-li"><i class="fas fa-phone-alt"></i></span>
                            {{ $customer->customerInfo->secondary_telephone_print }}
                        </li>
                        <li title="@lang('fields.birthday')">
                            <span class="fa-li"><i class="fas fa-birthday-cake"></i></span>
                            {{ convert_date($customer->customerInfo->birth_day, 'medium') }}
                        </li>
                        <li title="@lang('fields.city_district')">
                            <span class="fa-li"><i class="fas fa-city"></i></span>
                            {{ $customer->customerInfo->city->name }}/{{ $customer->customerInfo->district->name }}
                        </li>
                        <li title="@lang('fields.address')">
                            <span class="fa-li"><i class="fas fa-map-marker-alt"></i></span>
                            {{ $customer->customerInfo->address }}
                        </li>
                        <li title="@lang('fields.staff')">
                            <span class="fa-li"><i class="fas fa-stop"></i></span>
                            {{ $customer->staff->full_name }}
                        </li>
                    </ul>
                </div>
            </div>
            @if ($customer->subscriptions->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills" id="subsTab" role="tablist">
                            @foreach ($customer->subscriptions()->orderBy('id', 'desc')->get() as $subscription)
                                <li class="nav-item w-50 mb-2">
                                    <a class="nav-link customer-subs-tab text-center @if ($loop->first) active @endif"
                                        id="subs-tab-{{ $subscription->id }}" data-toggle="tab"
                                        href="#subs-{{ $subscription->id }}" role="tab" aria-controls="subs"
                                        aria-selected="true" data-id="{{ $subscription->id }}">
                                        <div>
                                            @lang("tables.subscription.status.{$subscription->status}")
                                        </div>
                                        <div class="profile-widget-item-value" title="@lang('fields.service')">
                                            <b>{{ $subscription->service->name }}</b>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        @if ($customer->subscriptions->count() > 0)
            <div class="col-lg-6">
                <div class="tab-content" id="subsTabContent">
                    @foreach ($customer->subscriptions()->orderBy('id', 'desc')->get() as $subscription)
                        @if ($subscription->approved_at == null)
                            @php $subscription->generatePayments(); @endphp
                        @endif
                        <div class="tab-pane pt-0 fade @if ($loop->first) show active @endif" id="subs-{{ $subscription->id }}" role="tabpanel"
                            aria-labelledby="subs-tab-{{ $subscription->id }}">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">
                                    <div class="profile-widget-items">
                                        <div class="profile-widget-item">
                                            @if ($subscription->subscription_no)
                                                <div>
                                                    #{{ $subscription->subscription_no }}
                                                </div>
                                            @endif
                                            <div class="badge mb-2">
                                                @lang("tables.subscription.status.{$subscription->status}")
                                            </div>
                                            <div class="profile-widget-item-value" title="@lang('fields.service')">
                                                {{ $subscription->service->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-widget-description">

                                    <div class="mb-3">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                @lang('fields.actions')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">

                                                @if ($subscription->status == 0)
                                                    <a href="{{ route('admin.subscription.edit', $subscription) }}"
                                                        class="dropdown-item">
                                                        <i class="dropdown-icon fas fa-edit"></i>
                                                        @lang('titles.edit')
                                                    </a>

                                                    <a target="_blank" class="dropdown-item"
                                                        href="{{ route('admin.subscription.contract', $subscription) }}">
                                                        <i class="dropdown-icon fas fa-file-contract"></i>
                                                        @lang('fields.contract_preview')
                                                    </a>

                                                    <button type="button"
                                                        class="dropdown-item confirm-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.approve.post', $subscription) }}"
                                                        data-modal="#approveSubscription">
                                                        <i class="dropdown-icon fas fa-check"></i>
                                                        @lang('titles.approve')
                                                    </button>

                                                    <button type="button"
                                                        class="dropdown-item confirm-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.delete', $subscription) }}"
                                                        data-modal="#delete">
                                                        <i class="dropdown-icon fas fa-trash"></i>
                                                        @lang('titles.delete')
                                                    </button>
                                                @endif

                                                @if ($subscription->approved_at)
                                                    <a href="{{ route('admin.subscription.payments', $subscription) }}"
                                                        class="dropdown-item">
                                                        <i class="dropdown-icon fas fa-file-invoice"></i>
                                                        @lang('tables.payment.title')
                                                    </a>

                                                    @if(!$subscription->isChanged())
                                                        <a target="_blank" class="dropdown-item"
                                                            href="/contracts/{{ md5($subscription->subscription_no) }}.pdf">
                                                            <i class="dropdown-icon fas fa-file-contract"></i>
                                                            @lang('fields.contract')
                                                        </a>
                                                    @endif

                                                    <button type="button"
                                                        class="dropdown-item confirm-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.unapprove.post', $subscription) }}"
                                                        data-modal="#unApproveSubscription">
                                                        <i class="dropdown-icon fas fa-redo-alt"></i>
                                                        @lang('titles.reset')
                                                    </button>
                                                @endif
                                                @if($subscription->isActive())
                                                    <a href="{{ route('admin.reference.add', $subscription) }}"
                                                        class="dropdown-item">
                                                        <i class="dropdown-icon fas fa-user-friends"></i>
                                                        @lang('tables.reference.add')
                                                    </a>

                                                    <button type="button"
                                                        class="dropdown-item edit-subscription-price-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.price', $subscription) }}"
                                                        data-customer="{{ $subscription->customer->full_name }}"
                                                        data-service="{{ $subscription->service->name }}"
                                                        data-price="{{ $subscription->price }}">
                                                        <i class="dropdown-icon fas fa-coins"></i>
                                                        @lang('titles.edit_subscription_price')
                                                    </button>

                                                    @if ($subscription->isAuto())
                                                        <button type="button"
                                                            class="dropdown-item confirm-modal-btn"
                                                            data-action="{{ relative_route('admin.subscription.cancel_auto_payment', $subscription) }}"
                                                            data-modal="#cancelAutoPayment">
                                                            <i class="dropdown-icon fas fa-wallet"></i>
                                                            @lang('titles.cancel_auto_payment')
                                                        </button>
                                                    @endif

                                                    <button type="button"
                                                        class="dropdown-item cancel-subscription-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.cancel.put', $subscription) }}"
                                                        data-customer="{{ $subscription->customer->select_print }}"
                                                        data-service="{{ $subscription->service_print }}">
                                                        <i class="dropdown-icon fas fa-times"></i>
                                                        @lang('titles.cancel_subscription')
                                                    </button>

                                                    @if (!$subscription->isFreezed())
                                                        <button type="button"
                                                            class="dropdown-item freeze-subscription-modal-btn"
                                                            data-action="{{ relative_route('admin.subscription.freeze.put', $subscription) }}"
                                                            data-customer="{{ $subscription->customer->select_print }}"
                                                            data-service="{{ $subscription->service_print }}">
                                                            <i class="dropdown-icon far fa-snowflake"></i>
                                                            @lang('titles.freeze_subscription')
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="dropdown-item confirm-modal-btn"
                                                            data-action="{{ relative_route('admin.subscription.unfreeze.put', $subscription) }}"
                                                            data-modal="#unFreezeSubscription">
                                                            <i class="dropdown-icon fas fa-retweet"></i>
                                                            @lang('titles.unfreeze_subscription')
                                                        </button>
                                                    @endif

                                                    @if(!$subscription->isChanged())
                                                        <a href="{{ route('admin.subscription.change', $subscription) }}"
                                                            class="dropdown-item">
                                                            <i class="dropdown-icon fas fa-cloud-upload-alt"></i>
                                                            @lang('tables.subscription.change_service')
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <ul class="fa-ul subscription-list mb-0">
                                        <li>
                                            <span class="fa-li"><i class="fas fa-map-marker-alt"></i></span>
                                            <div><b>@lang('fields.bbk_code')</b></div>
                                            <div>{{ $subscription->bbk_code }}<div>
                                        </li>
                                        <li>
                                            <span class="fa-li"><i class="fas fa-calendar-alt"></i></span>
                                            <div><b>@lang('fields.subscription_duration')</b></div>
                                            <div>
                                                <span
                                                    title="@lang('fields.start_date')">{{ $subscription->start_date_print }}</span>
                                                -
                                                @if ($subscription->commitment > 0)
                                                    <span
                                                        title="@lang('fields.end_date')">{{ $subscription->end_date_print }}</span>
                                                    <span>(@lang('fields.commitment_period'):
                                                        @lang("fields.commitments.{$subscription->commitment}"))</span>
                                                @else
                                                    @lang('fields.commitless')
                                                @endif
                                            </div>
                                        </li>
                                        @if ($subscription->approved_at != null)
                                            <li>
                                                <span class="fa-li"><i class="fas fa-calendar-check"></i></span>
                                                <div><b>@lang('fields.subscription_date')</b></div>
                                                <div>{{ $subscription->approved_at_print }}</div>
                                            </li>
                                        @endif
                                        <li>
                                            <span class="fa-li"><i class="fas fa-file-signature"></i></span>
                                            <div><b>@lang('fields.first_save_date')</b></div>
                                            <div>{{ convert_date($subscription->created_at, 'large') }}</div>
                                        </li>
                                        <li>
                                            <span class="fa-li"><i class="fas fa-coins"></i></span>
                                            <div><b>@lang('fields.price')</b></div>
                                            <div>{{ $subscription->price_print }}</div>
                                        </li>
                                        <li>
                                            <span class="fa-li"><i class="fas fa-receipt"></i></span>
                                            <div><b>@lang('fields.advance_paymented_price')</b></div>
                                            <div>
                                                {{ $subscription->payment_print }}
                                                @if ($subscription->approved_at == null)
                                                    <span>(@lang('fields.payable'))</span>
                                                @endif
                                            </div>
                                        </li>
                                        <li>
                                            <span class="fa-li"><i class="fas fa-network-wired"></i></span>
                                            <a href="#setupDetails_{{ $subscription->id }}" data-toggle="collapse"
                                                data-target="#setupDetails_{{ $subscription->id }}" aria-expanded="false"
                                                aria-controls="setupDetails_{{ $subscription->id }}">
                                                <div><b>@lang('fields.setup_informations')</b></div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="collapse" id="setupDetails_{{ $subscription->id }}">
                                        <table class="table table-sm mb-0">
                                            <tbody>
                                                @foreach ($subscription->option_values as $key => $option)
                                                    <tr>
                                                        <td width="50%"><b>{{ $option['title'] }}</b></td>
                                                        <td width="50%">{{ $option['value'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-12">
                @foreach ($customer->subscriptions()->orderBy('id', 'desc')->get() as $subscription)
                    @if ($subscription->payments->count())
                        <div class="card list subs-payments subs-{{ $subscription->id }}-payments">
                            <div class="card-header">
                                <h4>@lang('tables.payment.title')</h4>
                                @if ($subscription->isAuto())
                                    <span class="badge badge-primary" style="margin-right: auto;">@lang('fields.auto_paymented')</span>
                                @endif
                                <h4>{{ $subscription->service->name }}
                                    ({{ $subscription->price_print }})</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped dataTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('fields.date')</th>
                                                <th scope="col">@lang('fields.price')</th>
                                                <th scope="col">@lang('fields.payment_status')</th>
                                                <th scope="col">@lang('fields.payment_type')</th>
                                                <th scope="col">@lang('fields.payment_date')</th>
                                                <th scope="col">@lang('fields.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subscription->payments as $payment)
                                                <tr data-id="{{ $payment->id }}">
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td data-sort="{{ $payment->date }}">{{ $payment->date_print }}
                                                    </td>
                                                    <td data-sort="{{ $payment->price }}">{{ $payment->price_print }}
                                                    </td>
                                                    <td>@lang("tables.payment.status.{$payment->status}")</td>
                                                    <td>
                                                        @if ($payment->type)
                                                            <div>@lang("tables.payment.types.{$payment->type}")</div>
                                                        @endif
                                                    </td>
                                                    @if ($payment->paid_at)
                                                        <td data-sort="{{ $payment->paid_at }}">
                                                            <div title="@lang('fields.paid_date')">
                                                                {{ $payment->paid_at_print }}
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td>
                                                        <div class="buttons">
                                                            @if ($payment->paid_at == null)
                                                                <button type="button" class="btn btn-primary edit-payment-modal-btn"
                                                                    data-action="{{ relative_route('admin.payment.price.put', $payment) }}"
                                                                    data-price="{{ $payment->price }}"
                                                                    title="@lang('titles.edit_payment')">
                                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                                </button>
                                                            @endif

                                                            @if ($payment->status != 2)
                                                                <button type="button" class="btn btn-primary get-payment-modal-btn"
                                                                    data-action="{{ relative_route('admin.payment.received.post', $payment) }}"
                                                                    data-pre-auth-action="{{ relative_route('payment.pre.auth.create', $payment) }}"
                                                                    data-price="{{ $payment->price_print }}"
                                                                    title="@lang('titles.get_payment')">
                                                                    <i class="fas fa-cash-register"></i>
                                                                </button>
                                                            @endif

                                                            <button type="button" class="btn btn-danger delete-payment-modal-btn"
                                                                data-action="{{ relative_route('admin.subscription.payment.delete', $payment) }}"
                                                                data-subscription="{{ $subscription->select_print }}"
                                                                data-payment="{{ $payment->date_print }}"
                                                                title="@lang('titles.delete_payment')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="/assets/admin/vendor/datatables/datatables.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/datatables/DataTables-1.10.24/css/dataTables.bootstrap4.min.css">
@endpush

@push('script')
    <script src="/assets/admin/vendor/datatables/DataTables-1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="/assets/admin/vendor/datatables/DataTables-1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(function() {
            $(".dataTable").dataTable({
                language: {
                    url: '/assets/admin/vendor/datatables/i18n/tr.json'
                },
                columnDefs: [{
                    "type": "num",
                    "targets": 0
                }]
            });
        })

    </script>
@endpush

@push('modal')
    @include('admin.modals.get-payment')
    @include('admin.modals.edit-payment-price')
    @include('admin.modals.edit-subscription-price')
    @include('admin.modals.cancel-subscription')
    @include('admin.modals.freeze-subscription')
    @include('admin.modals.create-payment')
    @include('admin.modals.delete-payment')

    <x-admin.confirm-modal
        id="delete"
        method="delete"
        :title="trans('titles.actions.delete')"
        :message="trans('warnings.delete')"
        :buttonText="trans('titles.delete')"
        buttonType="danger" />

    <x-admin.confirm-modal
        id="approveSubscription"
        method="put"
        :title="trans('titles.actions.approve.subscription')"
        :message="trans('warnings.approve.customer')"
        :buttonText="trans('titles.approve')"
        buttonType="success" />

    <x-admin.confirm-modal
        id="unApproveSubscription"
        method="put"
        :title="trans('titles.actions.reset.subscription')"
        :message="trans('warnings.subscription.reset')"
        :buttonText="trans('titles.reset')"
        buttonType="danger" />

    <x-admin.confirm-modal
        id="unFreezeSubscription"
        method="put"
        :title="trans('titles.actions.approve.subscription')"
        :message="trans('warnings.approve.subscription')"
        :buttonText="trans('titles.approve')"
        buttonType="success" />

    <x-admin.confirm-modal
        id="cancelAutoPayment"
        method="put"
        :title="trans('titles.cancel_auto_payment')"
        :message="trans('warnings.payment.cancel_auto_payment')"
        :buttonText="trans('titles.approve')"
        buttonType="success" />
@endpush
