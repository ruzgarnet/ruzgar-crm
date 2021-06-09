@extends('admin.layout.main')

@section('title', meta_title('tables.reference.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.reference.title')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.staff')</th>
                                    <th scope="col">@lang('fields.reference')</th>
                                    <th scope="col">@lang('fields.referenced')</th>
                                    <th scope="col">@lang('fields.date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($references as $reference)
                                    <tr data-id="{{ $reference->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $reference->staff->select_print }}</td>
                                        <td>
                                            <a href="{{ route('admin.subscription.payments', $reference->reference) }}">
                                                {{ $reference->reference->select_print }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subscription.payments', $reference->referenced) }}">
                                                {{ $reference->referenced->select_print }}
                                            </a>
                                        </td>
                                        <td>{{ $reference->created_at }}</td>
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
