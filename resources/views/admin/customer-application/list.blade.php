@extends('admin.layout.main')

@section('title', meta_title('tables.customer_application.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.customer_application.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.customer_application.add') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-plus"></i> @lang('tables.customer_application.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.telephone')</th>
                                    <th scope="col">@lang('fields.staff')</th>
                                    <th scope="col">@lang('fields.type')</th>
                                    <th scope="col">@lang('fields.status')</th>
                                    <th scope="col">@lang('fields.description')</th>
                                    <th scope="col">Oluşturulma Tarihi</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer_applications as $index => $customer_application)
                                    <tr data-id="{{ $customer_application->id }}">
                                        <td>{{ $index }}</td>
                                        <td>
                                            @if ($customer_application->customer)
                                                <a href="{{ route('admin.customer.show', $customer_application->customer->id) }}"
                                                    target="_blank">
                                                    <span class="referenced-subscription">
                                                        {{ $customer_application->customer->select_print }}
                                                    </span>
                                                </a>
                                            @else
                                            {{ $customer_application->information["first_name"]." ".$customer_application->information["last_name"] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($customer_application->customer)
                                            <a href="{{ route('admin.customer.show', $customer_application->customer->id) }}"
                                                target="_blank">
                                                <span class="referenced-subscription">
                                                    {{ $customer_application->customer->telephone }}
                                                </span>
                                            </a>
                                        @else
                                         {{ $customer_application->information["telephone"] }}
                                        @endif

                                        </td>
                                        <td>

                                            @if ($customer_application->staff)
                                                {{ $customer_application->staff->full_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $customer_application->customerApplicationType->title }}</td>
                                        <td>@lang("tables.customer_application.status.{$customer_application->status}")</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm"
														data-toggle="popover" data-html="true"
														data-content="{{ $customer_application->description }}">
														@lang('titles.description')
													</button>
                                        </td>
                                        <td>{{ $customer_application->created_at }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.customer_application.edit', $customer_application) }}"
                                                    class="btn btn-primary" title="@lang('titles.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                },{ "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 8] }],
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        if(column[0][0] != 0 && column[0][0] != 1 && column[0][0] != 2 && column[0][0] != 6 && column[0][0] != 7 && column[0][0] != 8)
                        {
                            var select = $('<select class="form-control" style="width:80px;"><option value="">Tümü</option></select>')
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
                        if(column[0][0] == 1)
                        {
                            $('<input style="width:90px;" type="text" class="form-control" placeholder="Ara" />')
                            .appendTo( $(column.header()).empty() )
                            .on( 'input', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search('^'+val, true, false)
                                    .draw();
                            } );
                        }
                    } );
                }
            });
        })

    </script>
@endpush
