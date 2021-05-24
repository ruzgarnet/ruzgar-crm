@extends('admin.layout.main')

@section('title', meta_title('tables.category.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.category.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.category.add') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-plus"></i> @lang('tables.category.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.name')</th>
                                    <th scope="col">@lang('fields.slug')</th>
                                    <th scope="col">@lang('fields.parent_category')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr data-id="{{ $category->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if ($category->parent)
                                                {{ $category->parent->name }}
                                            @else
                                                -- @lang('fields.none') --
                                            @endif
                                        </td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.category.edit', $category) }}"
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
