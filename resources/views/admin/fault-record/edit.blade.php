@extends('admin.layout.main')

@section('title', meta_title('tables.fault.record.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.fault.record.edit', $faultRecord) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.fault.record.edit')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.fault.records') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.fault.record.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcCustomer">@lang('fields.customer')</label>
                            <select name="customer_id" id="slcCustomer" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.customer.select')</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @if ($faultRecord->customer_id == $customer->id) selected @endif>{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="slcFaultType">@lang('fields.fault_type')</label>
                            <select name="fault_type_id" id="slcFaultType" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.fault.type.select')</option>
                                @foreach ($faultTypes as $faultType)
                                    <option value="{{ $faultType->id }}" @if ($faultRecord->fault_type_id == $faultType->id) selected @endif>{{ $faultType->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="slcStatus">@lang('fields.status')</label>
                            <select name="status" id="slcStatus" class="custom-select selectpicker">
                                @foreach ($statuses as $key => $status)
                                    <option value="{{ $key }}" @if ($faultRecord->status == $key) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtDescription">@lang('fields.description')</label>
                            <textarea name="description" id="txtDescription" class="form-control"
                                rows="3" readonly>{{ $faultRecord->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="txtSolutionDetail">Not</label>
                            <textarea name="solution_detail" id="txtSolutionDetail" class="form-control"
                                rows="3" placeholder="">{{ $faultRecord->solution_detail }}</textarea>
                        </div>

                        <div class="d-flex flex-row">
                            @foreach ($faultRecord->files as $file)
                                <a target="_blank" href="/storage/{{ $file }}">
                                    <img width="50" class="mx-2" src="/storage/{{ $file }}" alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">@lang('fields.send')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="/assets/admin/vendor/select2/css/select2.min.css">
@endpush

@push('script')
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
@endpush
