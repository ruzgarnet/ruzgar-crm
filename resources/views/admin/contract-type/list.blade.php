@extends('admin.layout.main')

@section('title', meta_title('tables.contract_type.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('tables.contract_type.title')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.key')</th>
                                    <th scope="col">@lang('fields.title')</th>
                                    <th scope="col">@lang('fields.view')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contractTypes as $contractType)
                                    <tr data-id="{{ $contractType->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $contractType->key }}</td>
                                        <td>{{ $contractType->title }}</td>
                                        <td>{{ $contractType->view }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.contract.type.edit', $contractType) }}"
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
    <link rel="stylesheet" href="/assets/admin/vendor/datatables/DataTables-1.10.24\css\dataTables.bootstrap4.min.css">
@endpush

@push('script')
    <script src="/assets/admin/vendor/datatables/DataTables-1.10.24/js/jquery.DataTables.min.js"></script>
    <script src="/assets/admin/vendor/datatables/DataTables-1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(function() {
            $("#dataTable").dataTable({
                language: {
                    url: '/assets/admin/vendor/datatables/i18n/tr.json'
                }
            });
        })

    </script>
@endpush
