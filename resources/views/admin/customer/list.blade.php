@extends('admin.layout.main')

@section('title', meta_title('tables.customer.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('tables.customer.title')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.identification_number')</th>
                                    <th scope="col">@lang('fields.data_type')</th>
                                    <th scope="col">@lang('fields.name')</th>
                                    <th scope="col">@lang('fields.telephone')</th>
                                    <th scope="col">@lang('fields.city')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr data-id="{{ $customer->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $customer->identification_secret }}</td>
                                        <td>
                                            <span class="customer-type customer-type-{{ $customer->type }}">
                                                @lang("tables.customer.types.{$customer->type}")
                                            </span>
                                        </td>
                                        <td>{{ $customer->full_name }}</td>
                                        <td>{{ $customer->telephone_print }}</td>
                                        <td>{{ $customer->info->city->name }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.customer.edit', $customer) }}"
                                                    class="btn btn-primary" title="@lang('titles.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if ($customer->type === 1)
                                                    <button type="button" class="btn btn-success approve-modal-btn"
                                                        data-action="{{ route('admin.customer.approve.post', $customer) }}"
                                                        data-modal="#approveCustomerModal" title="@lang('titles.approve')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
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
    @include('admin.modals.approve-customer')
@endpush
