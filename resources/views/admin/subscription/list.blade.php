@extends('admin.layout.main')

@section('title', meta_title('tables.subscription.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">
                <div class="card-header">
                    <h4>@lang('tables.subscription.title')</h4>

                    <div class="card-header-buttons">
                        <a href="{{ route('admin.subscription.add') }}" class="btn btn-primary"><i
                                class="fas fa-sm fa-plus"></i> @lang('tables.subscription.add')</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.service')</th>
                                    <th scope="col">@lang('fields.price')</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.start_date')</th>
                                    <th scope="col">@lang('fields.end_date')</th>
                                    <th scope="col">@lang('fields.approve_date')</th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr data-id="{{ $subscription->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $subscription->service->name }}</td>
                                        <td>{{ $subscription->price_print }}</td>
                                        <td>{{ $subscription->customer->full_name }}</td>
                                        <td>{{ convert_date($subscription->start_date, 'mask') }}</td>
                                        <td>
                                            @if ($subscription->end_date)
                                                {{ convert_date($subscription->end_date, 'mask') }}
                                            @else
                                                <span class="badge badge-primary">@lang('fields.commitless')</span>
                                            @endif
                                        </td>
                                        <td>{{ convert_date($subscription->approved_at, 'mask_time') }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="{{ route('admin.subscription.edit', $subscription) }}"
                                                    class="btn btn-primary edit-row-btn" title="@lang('titles.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if ($subscription->approved_at === null)
                                                    <button type="button" class="btn btn-danger delete-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.delete', $subscription) }}"
                                                        title="@lang('titles.delete')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-success approve-modal-btn"
                                                        data-action="{{ relative_route('admin.subscription.approve.post', $subscription) }}"
                                                        data-modal="#approveSubscriptionModal"
                                                        title="@lang('titles.approve')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-danger approve-modal-btn"
                                                    data-action="{{ relative_route('admin.subscription.unapprove.post', $subscription) }}"
                                                    data-modal="#approveSubscriptionModal">
                                                    <i class="fas fa-check"></i>
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

@push('modal')
    @include('admin.modals.delete')
    @include('admin.modals.approve-subscription')
@endpush

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
