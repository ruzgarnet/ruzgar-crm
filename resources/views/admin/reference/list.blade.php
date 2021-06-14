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
                                    <th scope="col">@lang('fields.status')</th>
                                    <th scope="col">@lang('fields.reference')</th>
                                    <th scope="col">@lang('fields.referenced')</th>
                                    <th scope="col">@lang('fields.passed_time')</th>
                                    <th scope="col"></th>
                                    <th scope="col">@lang('fields.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($references as $reference)
                                    <tr data-id="{{ $reference->id }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            @if ($reference->status != 1)
                                                <span class="reference-statuses reference-status-{{ $reference->status }}"
                                                    data-toggle="popover" data-html="true" data-content="<b>Tarih:</b>
                                                                {{ convert_date($reference->decided_at, 'large') }}
                                                                <br>
                                                                <b>Personel</b>: {{ $reference->staff->full_name }}
                                                                <br>
                                                                <b>Açıklama</b>: @lang("tables.reference.status.descriptions.{$reference->status}")">
                                                    @lang("tables.reference.status.titles.{$reference->status}")
                                                </span>
                                            @else
                                                <span class="reference-statuses reference-status-{{ $reference->status }}"
                                                    title="@lang("
                                                    tables.reference.status.descriptions.{$reference->status}")">
                                                    @lang("tables.reference.status.titles.{$reference->status}")
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subscription.payments', $reference->reference) }}"
                                                target="_blank">
                                                <span class="reference-subscription">
                                                    {{ $reference->reference->select_print }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subscription.payments', $reference->referenced) }}"
                                                target="_blank">
                                                <span class="referenced-subscription">
                                                    {{ $reference->referenced->select_print }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <span title="@lang('fields.date'): {{ convert_date($reference->created_at, 'large') }}">
                                                {{ $reference->created_at->longAbsoluteDiffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($reference->status == 1 && $reference->created_at->diffInMonths() > 1)
                                                <span class="text-primary">@lang('warnings.reference.control_time')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary edit-reference-modal-btn"
                                                data-action="{{ relative_route('admin.reference.edit.put', $reference) }}"
                                                data-status="{{ $reference->status }}" title="@lang('titles.edit')">
                                                <i class="fas fa-edit"></i>
                                            </button>
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
    @include('admin.modals.edit-reference')
@endpush
