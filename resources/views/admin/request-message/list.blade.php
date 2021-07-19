@extends('admin.layout.main')

@section('title', meta_title('tables.request.message.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.request.message.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.request.message.add') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-plus"></i> @lang('tables.request.message.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.staff')</th>
                                    <th scope="col">@lang('fields.role')</th>
                                    <th scope="col">@lang('fields.message')</th>
                                    <th scope="col">@lang('fields.status')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestMessages as $requestMessage)
                                    <tr data-id="{{ $requestMessage->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $requestMessage->staff->full_name }}</td>
                                        <td>{{ $requestMessage->role->name }}</td>
                                        <td>{{ $requestMessage->message }}</td>
                                        <td>@lang('tables.request.message.status.'.$requestMessage->status)</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.request.message.edit', $requestMessage) }}"
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
                }]
            });
        })

    </script>
@endpush
