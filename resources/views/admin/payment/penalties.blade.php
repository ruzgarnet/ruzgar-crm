@extends('admin.layout.main')

@section('title', meta_title('tables.payment.penalty'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.payment.penalty')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.identification_number')</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.telephone')</th>
                                    <th scope="col">@lang('fields.secondary_telephone')</th>
                                    <th scope="col">@lang('fields.service')</th>
                                    <th scope="col">@lang('fields.price')</th>
                                    <th scope="col">@lang('fields.payment_status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr data-id="{{ $payment->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $payment->subscription->customer->identification_number }}</td>
                                        <td>
                                            <a href="{{ route('admin.customer.show', $payment->subscription->customer) }}"
                                                >{{ $payment->subscription->customer->full_name }}</a>
                                        </td>
                                        <td>{{ $payment->subscription->customer->telephone_print }}</td>
                                        <td>{{ $payment->subscription->customer->customerInfo->secondary_telephone_print }}</td>
                                        <td>{{ $payment->subscription->service->category->name }}</td>
                                        <td data-sort="{{ $payment->price }}">{{ print_money($payment->price) }}</td>
                                        <td @if ($payment->isPaid()) title="@lang("tables.payment.types.{$payment->type}") &#013;{{ $payment->paid_at_print }}" @endif>
                                            @lang("tables.payment.penalty_status.{$payment->status}")
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
                columnDefs: [
                    {"type": "num", "targets": 0},
                    {"orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7]}
                ],
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        if(column[0][0] == 5 || column[0][0] == 7) {
                            var select = $('<select class="form-control" style="width:100px;"><option value="">Tümü</option></select>')
                                .appendTo( $(column.header()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
             
                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
         
                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        }
                    } );
                }
            });
        })

    </script>
@endpush
