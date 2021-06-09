@extends('admin.layout.main')

@section('title', meta_title('tables.subscription.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.subscription.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.subscription.add') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-plus"></i> @lang('tables.subscription.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.service')</th>
                                    <th scope="col">@lang('fields.price')</th>
                                    <th scope="col">@lang('fields.start_date')</th>
                                    <th scope="col">@lang('fields.end_date')</th>
                                    <th scope="col">@lang('fields.approve_date')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr data-id="{{ $subscription->id }}"
                                        class="{{ $subscription->approved_at === null ? 'un-approved-row' : 'approved-row' }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <a
                                                href="{{ route('admin.customer.show', $subscription->customer_id) }}">{{ $subscription->customer->full_name }}</a>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $subscription->service->name }}
                                                @if ($subscription->isCanceled())
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="popover" data-html="true"
                                                        data-content="<b>Tarih:</b> {{ convert_date($subscription->canceledSubscription->created_at, 'large') }} <br>
                                                                <b>Personel</b>: {{ $subscription->canceledSubscription->staff->full_name }} <br>
                                                                <b>Sebep</b>: {{ $subscription->canceledSubscription->description }}">
                                                        @lang('titles.cancel')
                                                    </button>
                                                @endif
                                            </div>
                                            @if ($subscription->isChanged())
                                                <div>
                                                    <small>
                                                        <a
                                                            href="{{ route('admin.subscription.payments', $subscription->getChanged()) }}">
                                                            {{ $subscription->getChanged()->service->name }}
                                                        </a>
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $subscription->price_print }}</td>
                                        <td>{{ convert_date($subscription->start_date, 'mask') }}</td>
                                        <td>
                                            @if ($subscription->end_date)
                                                {{ convert_date($subscription->end_date, 'mask') }}
                                            @else
                                                <span class="badge badge-primary">@lang('fields.commitless')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($subscription->approved_at !== null)
                                                {{ convert_date($subscription->approved_at, 'mask_time') }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="buttons">
                                                {{-- FIXME prepare for production --}}
                                                <a href="{{ route('admin.subscription.edit', $subscription) }}"
                                                    class="btn btn-primary un-approved-element"
                                                    title="@lang('titles.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button"
                                                    class="btn btn-danger un-approved-element delete-modal-btn"
                                                    data-action="{{ relative_route('admin.subscription.delete', $subscription) }}"
                                                    title="@lang('titles.delete')">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                <button type="button"
                                                    class="btn btn-success approve-modal-btn un-approved-element"
                                                    data-action="{{ relative_route('admin.subscription.approve.post', $subscription) }}"
                                                    data-modal="#approveSubscriptionModal" title="@lang('titles.approve')">
                                                    <i class="fas fa-check"></i>
                                                </button>

                                                <a href="{{ route('admin.subscription.payments', $subscription) }}"
                                                    class="btn btn-primary approved-element"
                                                    title="@lang('tables.payment.title')">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>

                                                @if ($subscription->isEditable())
                                                    <button type="button"
                                                        class="btn btn-info approved-element edit-subscription-price-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.price', $subscription) }}"
                                                        data-customer="{{ $subscription->customer->full_name }}"
                                                        data-service="{{ $subscription->service->name }}"
                                                        data-price="{{ $subscription->price }}"
                                                        title="@lang('titles.edit_subscription_price')">
                                                        <i class="fas fa-coins"></i>
                                                    </button>

                                                    <button type="button"
                                                        class="btn btn-danger approved-element cancel-subscription-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.cancel.put', $subscription) }}"
                                                        data-customer="{{ $subscription->customer->select_print }}"
                                                        data-service="{{ $subscription->service->select_print }}"
                                                        title="@lang('titles.cancel_subscription')">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <a href="{{ route('admin.subscription.change', $subscription) }}"
                                                        class="btn btn-primary approved-element"
                                                        title="@lang('tables.subscription.change_service')">
                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                    </a>
                                                @endif

                                                @if ($subscription->approved_at)
                                                    <a target="_blank" class="approve-element btn btn-secondary"
                                                        href="/contracts/{{ md5($subscription->subscription_no) }}.pdf"
                                                        title="@lang(" fields.contract")">
                                                        <i class="fas fa-file-contract"></i>
                                                    </a>
                                                @endif


                                                <button type="button"
                                                    class="btn btn-warning approve-modal-btn approved-element"
                                                    data-action="{{ relative_route('admin.subscription.unapprove.post', $subscription) }}"
                                                    data-modal="#approveSubscriptionModal">
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
        </div>
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
            $("#dataTable").dataTable({
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
    @include('admin.modals.delete')
    @include('admin.modals.approve-subscription')
    @include('admin.modals.edit-subscription-price')
    @include('admin.modals.cancel-subscription')
@endpush
