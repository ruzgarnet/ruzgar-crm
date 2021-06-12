@extends('admin.layout.main')

@section('title', meta_title('tables.fault.record.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.fault.record.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.fault.record.add')</h4>

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
                                    <option value="{{ $customer->id }}">{{ $customer->select_print }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="slcFaultType">@lang('fields.fault_type')</label>
                            <select name="fault_type_id" id="slcFaultType" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.fault.type.select')</option>
                                @foreach ($faultTypes as $faultType)
                                    <option value="{{ $faultType->id }}">{{ $faultType->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtDescription">@lang('fields.description')</label>
                            <textarea name="description" id="txtDescription" class="form-control"
                                rows="3"></textarea>
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
