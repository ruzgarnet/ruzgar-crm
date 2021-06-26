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
                    <div>
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.service')</th>
                                    <th scope="col"></th>
                                    <th scope="col">@lang('fields.price')</th>
                                    <th scope="col">@lang('fields.subscription_duration')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr data-id="{{ $subscription->id }}"
                                        class="{{ $subscription->approved_at == null ? 'un-approved-row' : 'approved-row' }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <a
                                                href="{{ route('admin.customer.show', $subscription->customer_id) }}">{{ $subscription->customer->full_name }}</a>
                                        </td>
                                        <td>{{ $subscription->service->name }}</td>
                                        <td>
                                            <div class="buttons">
												@if ($subscription->isCanceled())
													<button type="button" class="btn btn-danger btn-sm"
														data-toggle="popover" data-html="true"
														data-content="<b>Tarih:</b> {{ convert_date($subscription->cancellation->created_at, 'large') }} <br>
																														<b>Personel</b>: {{ $subscription->cancellation->staff->full_name }} <br>
																														<b>Sebep</b>: {{ $subscription->cancellation->description }}">
														@lang('titles.cancel')
													</button>
												@endif

												@if ($subscription->isChanged())
													<a class="btn btn-info btn-sm" title="@lang('fields.changed_service')"
														href="{{ route('admin.subscription.payments', $subscription->getChanged()) }}">
														{{ $subscription->getChanged()->service->name }}
													</a>
												@endif

                                                @if ($subscription->isFreezed())
													<button type="button" class="btn btn-warning btn-sm"
														data-toggle="popover" data-html="true"
														data-content="<b>Tarih:</b> {{ convert_date($subscription->freeze->created_at, 'large') }} <br>
																														<b>Personel</b>: {{ $subscription->freeze->staff->full_name }} <br>
																														<b>Sebep</b>: {{ $subscription->freeze->description }}">
														@lang('titles.freezed')
													</button>
												@endif

                                                @if (!$subscription->approved_at)
                                                    <button type="button" class="btn btn-secondary">
                                                        @lang('titles.unapproved')
                                                    </button>
                                                @endif
											</div>
                                        </td>
                                        <td>{{ $subscription->price_print }}</td>
                                        <td>
                                            {{ convert_date($subscription->start_date, 'medium') }}
                                            -
                                            @if ($subscription->end_date)
                                                {{ convert_date($subscription->end_date, 'medium') }}
                                            @else
                                                <span class="badge badge-primary">@lang('fields.commitless')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="buttons">
                                                {{-- FIXME prepare for production --}}
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

                                                            @if($subscription->is_auto())
                                                                <button type="button"
                                                                    class="dropdown-item confirm-modal-btn"
                                                                    data-action="{{ relative_route('admin.subscription.cancel_auto_payment', $subscription) }}"
                                                                    data-modal="#cancelAutoPayment">
                                                                    <i class="dropdown-icon fas fa-times"></i>
                                                                    @lang('titles.cancel_auto_payment')
                                                                </button>
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
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
        :message="trans('warnings.approve.subscription')"
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
        id="cancelAutoPayment"
         method="put"
        :title="trans('titles.cancel_auto_payment')"
        :message="trans('warnings.subscription.cancel_auto_payment')"
        :buttonText="trans('titles.cancel_auto_payment')"
        buttonType="success" />
@endpush
