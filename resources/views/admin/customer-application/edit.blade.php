@extends('admin.layout.main')

@section('title', meta_title('tables.customer_application.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.customer.application.edit', $customer_application) }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.customer_application.edit')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcStaff">@lang('fields.staff')</label>
                            <select name="staff_id" id="slcStaff" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.staff.select')</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}" @if ($customer_application->staff_id == $staff->id) selected @endif>{{ $staff->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="slcCustomerApplicationType">@lang('fields.fault_type')</label>
                            <select name="customer_application_type_id" id="slcCustomerApplicationType" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.fault.type.select')</option>
                                @foreach ($customer_application_types as $customer_application_type)
                                    <option value="{{ $customer_application_type->id }}" @if ($customer_application->customer_application_type_id == $customer_application_type->id) selected @endif>{{ $customer_application_type->title }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="slcStatus">@lang('fields.status')</label>
                            <select name="status" id="slcStatus" class="custom-select selectpicker">
                                @foreach ($statuses as $key => $status)
                                    <option value="{{ $key }}" @if ($customer_application->status == $key) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtDescription">@lang('fields.description')</label>
                            <textarea name="description" id="txtDescription" class="form-control" rows="3">{{ $customer_application->description }}</textarea>
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
    <script src="/assets/admin/vendor/ckeditor/ckeditor.js"></script>
    <script src="/assets/admin/vendor/slugify/slugify.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
@endpush
