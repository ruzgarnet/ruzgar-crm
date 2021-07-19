@extends('admin.layout.main')

@section('title', meta_title('tables.subscription.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.subscription.title')</h4>
                    <div class="card-header-buttons">
                        <span>
                            <select id="slcStatus" class="custom-select">
                                <option value="">Tümü</option>
                                <option value="0">Onaylanmamış</option>
                                <option value="1">Onaylandı</option>
                                <option value="2">Hizmeti Değiştirildi</option>
                                <option value="3">İptal Edildi</option>
                                <option value="4">Donduruldu</option>
                            </select>
                        </span>
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
                                    <th scope="col">Satış</th>
                                    <th scope="col">@lang('fields.actions')</th>
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
                ajax: "/subscription/list",
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
                        if (column[0][0] != 0 && column[0][0] != 1 && column[0][0] != 2 &&
                            column[0][0] != 4 && column[0][0] != 5 && column[0][0] != 7 &&
                            column[0][0] != 12) {
                            var select = $('#slcStatus')
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val, true, false)
                                    .draw();
                            });
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
