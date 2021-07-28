@extends('admin.layout.main')

@section('title', meta_title('tables.payment.monthly'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.payment.monthly')</h4>

                    <form method="POST" action="{{ route('admin.payment.monthly') }}" data-ajax="false" class="card-header-buttons">
                        @csrf
                        <input type="date" name="date" name="dtDate" class="form-control" value="{{ $date ?? date('Y-m-15') }}">
                        <button type="submit" class="btn btn-primary">Listele</button>
                    </form>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Hizmet</th>
                                    <th scope="col">TC</th>
                                    <th scope="col">İsim</th>
                                    <th scope="col">Tel 1</th>
                                    <th scope="col">Tel 2</th>
                                    <th scope="col">Tarife</th>
                                    <th scope="col">Tutar</th>
                                    <th scope="col">Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $payment->subscription->service->category->name }}</td>
                                        <td>{{ $payment->subscription->customer->identification_number }}</td>
                                        <td>{{ $payment->subscription->customer->full_name }}</td>
                                        <td>{{ $payment->subscription->customer->telephone_print }}</td>
                                        <td>{{ $payment->subscription->customer->customerInfo->secondary_telephone_print }}</td>
                                        <td>{{ $payment->subscription->service->name }}</td>
                                        <td>{{ print_money($payment->price) }}</td>
                                        <td>@lang("tables.payment.status.{$payment->status}")</td>
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
    <link rel="stylesheet" href="/assets/admin/vendor/datatables/Buttons-1.7.1/css/buttons.dataTables.min.css">
@endpush

@push('script')
    <script src="/assets/admin/vendor/datatables/DataTables-1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="/assets/admin/vendor/datatables/DataTables-1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="/assets/admin/vendor/datatables/Buttons-1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="/assets/admin/vendor/datatables/Buttons-1.7.1/js/buttons.html5.min.js"></script>
    <script src="/assets/admin/vendor/datatables/Buttons-1.7.1/js/buttons.bootstrap4.min.js"></script>

    <script>
       $(document).ready(function() {
            $('#dataTable').DataTable( {
                language: {
                    url: '/assets/admin/vendor/datatables/i18n/tr.json'
                },
                columnDefs: [{
                    "type": "num",
                    "targets": 0
                }],
                dom: '<"dataTableFlex"Bl><rt><"dataTableFlex"ip>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Ödemeler'
                    }
                ],
                columnDefs: [
                    {"type": "num", "targets": 0},
                    {"orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7, 8]}
                ],
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        if(column[0][0] == 1 || column[0][0] == 6 || column[0][0] == 8) {
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
        });

    </script>
@endpush
