@extends('admin.layout.main')

@section('title', meta_title('tables.dealer.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('tables.dealer.title')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.name')</th>
                                    <th scope="col">@lang('fields.tax_number')</th>
                                    <th scope="col">@lang('fields.city')</th>
                                    <th scope="col">@lang('fields.telephone')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dealers as $dealer)
                                    <tr data-id="{{ $dealer->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $dealer->name }}</td>
                                        <td>{{ $dealer->tax_number }}</td>
                                        <td>{{ $dealer->city->name }}</td>
                                        <td>{{ $dealer->telephone_print }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.dealer.edit', $dealer) }}"
                                                    class="btn btn-primary" title="@lang('titles.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger delete-modal-btn" data-action="{{ relative_route('admin.dealer.delete', $dealer) }}" title="@lang('titles.delete')">
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

@push('modal')
    @include('admin.modals.delete')
@endpush
