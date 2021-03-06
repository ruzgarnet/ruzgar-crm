@extends('admin.layout.main')

@section('title', meta_title('tables.customer.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.customer.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.customer.add') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-plus"></i> @lang('tables.customer.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.identification_number')</th>
                                    <th scope="col">@lang('fields.name')</th>
                                    <th scope="col">@lang('fields.telephone')</th>
                                    <th scope="col">@lang('fields.city')</th>
                                    <th scope="col">@lang('fields.staff')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                    {{-- @foreach ($customers as $customer)
                                        <tr data-id="{{ $customer->id }}"
                                            class="{{ $customer->type == 1 ? 'un-approved-row' : 'approved-row' }}">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                <span class="d-inline-block text-center">
                                                    {{ $customer->identification_secret }}
                                                    @if ($customer->type == 1)
                                                        <div class="customer-type customer-type-{{ $customer->type }}">
                                                            @lang("tables.customer.types.{$customer->type}")
                                                        </div>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $customer->full_name }}</td>
                                            <td data-filter="0{{ $customer->telephone }}">{{ $customer->telephone_print }}
                                            </td>
                                            <td>{{ $customer->customerInfo->city->name }}</td>
                                            <td>
                                                @if ($customer->staff)
                                                    {{ $customer->staff->full_name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="buttons">
                                                    <a href="{{ route('admin.customer.edit', $customer) }}"
                                                        class="btn btn-primary" title="@lang('titles.edit')">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <a href="{{ route('admin.customer.show', $customer) }}"
                                                        class="btn btn-primary" title="@lang('titles.show')">
                                                        <i class="fas fa-file"></i>
                                                    </a>

                                                    @if ($customer->type == 1)
                                                        <button type="button"
                                                            class="btn btn-success confirm-modal-btn"
                                                            data-action="{{ route('admin.customer.approve.post', $customer) }}"
                                                            data-modal="#approveCustomer" title="@lang('titles.approve')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach --}}
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
                processing: true,
                serverSide: true,
                ajax: "/customer/list",
                language: {
                    url: '/assets/admin/vendor/datatables/i18n/tr.json'
                },
                dom:'ftip',
                columnDefs: [{
                    "type": "num",
                    "targets": 0
                },{ "orderable": false, "targets": [0, 1, 2, 3, 4, 5, 6] }],
            });
        })

    </script>
@endpush

@push('modal')
    <x-admin.confirm-modal
        id="approveCustomer"
        method="put"
        :title="trans('titles.actions.approve.customer')"
        :message="trans('warnings.approve.customer')"
        :buttonText="trans('titles.approve')"
        buttonType="success" />
@endpush
