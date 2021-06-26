@extends('admin.layout.main')

@section('title', meta_title('tables.user.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.user.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.user.add') }}" class="btn btn-primary"><i class="fas fa-sm fa-plus"></i>
                            @lang('tables.user.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.username')</th>
                                    <th scope="col">@lang('fields.email')</th>
                                    <th scope="col">@lang('fields.staff')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr data-id="{{ $user->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->staff->full_name }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary"
                                                    title="@lang('titles.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger confirm-modal-btn"
                                                    data-action="{{ relative_route('admin.user.delete', $user) }}"
                                                    data-modal="#delete" title="@lang('titles.delete')">
                                                    <i class="fas fa-trash"></i>
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
    <x-admin.confirm-modal
        id="delete"
        method="delete"
        :title="trans('titles.actions.delete')"
        :message="trans('warnings.delete')"
        :buttonText="trans('titles.delete')"
        buttonType="danger" />
@endpush
