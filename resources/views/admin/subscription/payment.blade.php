@extends('admin.layout.main')

@section('title', meta_title('tables.payment.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.subscription.info')</h4>

                    <div class="card-header-buttons">
                        <button class="btn btn-primary" type="button" data-toggle="collapse"
                            data-target="#subscriptionDetails" aria-expanded="false" aria-controls="subscriptionDetails"><i
                                class="fas fa-sm fa-clipboard-list"></i>
                            @lang('titles.accessibility.collapse.subscription_info')</button>

                        <a href="{{ route('admin.subscriptions') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-list-ul"></i> @lang('tables.subscription.title')</a>
                    </div>
                </div>
                <div class="collapse" id="subscriptionDetails">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th colspan="4">@lang('fields.details')</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="25%">@lang('fields.subscriber')</td>
                                        <td width="25%"><b>{{ $subscription->customer->full_name }}</b></td>
                                        <td width="25%">@lang('fields.subscription_no')</td>
                                        <td width="25%"><b>{{ $subscription->subscription_no }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>@lang('fields.bbk_code')</td>
                                        <td><b>{{ $subscription->bbk_code }}</b></td>
                                        <td>@lang('fields.subscription_date')</td>
                                        <td><b>{{ $subscription->approved_at_print }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>@lang('fields.service')</td>
                                        <td><b>{{ $subscription->service->name }}</b></td>
                                        <td>@lang('fields.price')</td>
                                        <td><b>{{ $subscription->price_print }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>@lang('fields.advance_paymented_price')</td>
                                        <td><b>{{ $subscription->payment_print }}</b></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td>@lang('fields.start_date')</td>
                                        <td><b>{{ convert_date($subscription->start_date, 'medium') }}</b></td>
                                        <td>@lang('fields.end_date')</td>
                                        <td>
                                            <b>
                                                @if ($subscription->end_date)
                                                    {{ convert_date($subscription->end_date, 'medium') }}
                                                @else
                                                    @lang('fields.commitless')
                                                @endif
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('fields.setup')</td>
                                        <td class="p-0" colspan="3">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">@lang('fields.setup_informations')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($subscription->option_values as $key => $option)
                                                        <tr>
                                                            <td width="50%">{{ $option['title'] }}</td>
                                                            <td width="50%"><b>{{ $option['value'] }}</b></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if ($subscription->approved_at)
                <div class="card list">
                    <div class="card-header">
                        <h4>@lang('tables.payment.title')</h4>
                        <h4>{{ $subscription->customer->full_name }} - {{ $subscription->service->name }}
                            ({{ $subscription->price_print }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">@lang('fields.date')</th>
                                        <th scope="col">@lang('fields.price')</th>
                                        <th scope="col">@lang('fields.payment_status')</th>
                                        <th scope="col">@lang('fields.payment_type')</th>
                                        <th scope="col">@lang('fields.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscription->payments as $payment)
                                        <tr data-id="{{ $payment->id }}">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td data-sort="{{ $payment->date }}">{{ $payment->date_print }}</td>
                                            <td data-sort="{{ $payment->price }}">{{ $payment->price_print }}</td>
                                            <td>@lang("tables.payment.status.{$payment->status}")</td>
                                            <td>
                                                @if ($payment->type)
                                                    <div>@lang("tables.payment.types.{$payment->type}")</div>
                                                    <div title="@lang('fields.paid_date')">{{ $payment->paid_at_print }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="buttons">
                                                    <button type="button"
                                                        class="btn btn-primary edit-payment-modal-btn"
                                                        data-action="{{ relative_route('admin.payment.price.put', $payment) }}"
                                                        data-price="{{ $payment->price }}"
                                                        title="@lang('titles.edit_payment')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    @if ($payment->status !== 2)
                                                        <button type="button"
                                                            class="btn btn-primary get-payment-modal-btn"
                                                            data-action="{{ relative_route('admin.payment.received.post', $payment) }}"
                                                            data-price="{{ $payment->price_print }}"
                                                            title="@lang('titles.get_payment')">
                                                            <i class="fas fa-cash-register"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger">
                    @lang('warnings.unapproved.subscription')
                </div>
            @endif

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
    @include('admin.modals.get-payment')
    @include('admin.modals.edit-payment-price')
@endpush
