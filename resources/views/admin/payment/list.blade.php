@extends('admin.layout.main')

@section('title', meta_title('tables.payment.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.payment.title')</h4>
                    <div class="card-header-buttons">
                        <input type="date" class="custom-select" id="inpDate">
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <a class="btn btn-success" href="{{ route('admin.excel') }}" role="button">EXCEL</a>
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.service')</th>
                                    <th scope="col">@lang('fields.price')</th>
                                    <th scope="col">@lang('fields.date')</th>
                                    <th scope="col">@lang('fields.status')</th>
                                    <th scope="col">@lang('fields.type')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                processing: true,
                serverSide: true,
                ajax: "/payment/list",
                language: {
                    url: '/assets/admin/vendor/datatables/i18n/tr.json'
                },
                dom: 'ftipPr',
                columnDefs: [{
                    "type": "num",
                    "targets": 0
                }, {
                    "orderable": false,
                    "targets": [0, 1, 2, 3, 4, 5, 6]
                }],
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        if (column[0][0] != 0 && column[0][0] != 1 && column[0][0] != 3 && column[0][0] != 5 && column[0][0] != 6 && column[0][0] != 4) {
                            var select = $(
                                    '<select class="form-control" style="width:110px;"><option value="">Tümü</option></select>'
                                    )
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val, true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function(d, j) {
                                select.append('<option value="' + (j+1) + '">' + d +
                                    '</option>')
                            });
                        }
                        if(column[0][0] == 4 )
                        {
                            var input = $('#inpDate')
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val, true, false)
                                        .draw();
                                });
                        }
                        if(column[0][0] == 2)
                        {
                            var select = $('<select class="form-control" style="width:110px;"><option value="">Tümü</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val, true, false)
                                        .draw();
                                });

                                @foreach ($services as $service)
                                    select.append('<option value="{{ $service->id }}">{{ $service->name }}</option>');
                                @endforeach
                        }
                        if(column[0][0] == 5)
                        {
                            var select = $('<select class="form-control" style="width:110px;"><option value="">Tümü</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val, true, false)
                                        .draw();
                                });

                                select.append('<option value="1">Sisteme Tanımlandı</option>');
                                select.append('<option value="2">Ödeme Başarıyla Alındı</option>');
                                select.append('<option value="3">Ödeme Alınırken Hata Oluştu</option>');
                        }
                        if(column[0][0] == 6 )
                        {
                            var select = $(
                                    '<select class="form-control" style="width:110px;"><option value="">Tümü</option></select>'
                                    )
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val, true, false)
                                        .draw();
                                });

                                select.append('<option value="1">Nakit</option>');
                                select.append('<option value="2">Kredi/Banka Kartı (Pos)</option>');
                                select.append('<option value="3">Havale/EFT</option>');
                                select.append('<option value="4">Kredi/Banka Kartı (Online)</option>');
                                select.append('<option value="5">Otomatik Ödeme</option>');
                                select.append('<option value="6">Nakit (Ön Ödeme)</option>');
                        }
                    });
                }
            });
        })
    </script>
@endpush

@push('modal')
    <x-admin.confirm-modal id="delete" method="delete" :title="trans('titles.actions.delete')"
        :message="trans('warnings.delete')" :buttonText="trans('titles.delete')" buttonType="danger" />

    <x-admin.confirm-modal id="approveSubscription" method="put" :title="trans('titles.actions.approve.subscription')"
        :message="trans('warnings.approve.subscription')" :buttonText="trans('titles.approve')" buttonType="success" />

    <x-admin.confirm-modal id="unApproveSubscription" method="put" :title="trans('titles.actions.reset.subscription')"
        :message="trans('warnings.subscription.reset')" :buttonText="trans('titles.reset')" buttonType="danger" />
@endpush
