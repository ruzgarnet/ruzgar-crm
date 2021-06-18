@extends('admin.layout.main')

@section('title', meta_title('tables.customer_application.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.customer_application.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.customer_application.add')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcCustomer">@lang('fields.customer')</label>
                            <select name="customer_id" id="slcCustomer" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.subscription.select')</option>
                                @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->select_print }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpFirstName">@lang('fields.first_name')</label>
                                    <input type="text" name="first_name" id="inpFirstName" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpLastName">@lang('fields.last_name')</label>
                                    <input type="text" name="last_name" id="inpLastName" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inpTelephone">@lang('fields.telephone')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                </div>
                                <input type="text" name="telephone" id="inpTelephone"
                                    class="form-control telephone-mask">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slcStaff">@lang('fields.staff')</label>
                            <select name="staff_id" id="slcStaff" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.subscription.select')</option>
                                @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->select_print }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="slcCustomerApplicationType">@lang('fields.type')</label>
                            <select name="customer_application_type_id" id="slcCustomerApplicationType" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.customer_application_type.select')</option>
                                @foreach ($customer_application_types as $customer_application_type)
                                        <option value="{{ $customer_application_type->id }}">{{ $customer_application_type->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="slcStatus">@lang('fields.status')</label>
                            <select name="status" id="slcStatus" class="custom-select selectpicker">
                                @foreach ($statuses as $key => $status)
                                    <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtDescription">@lang('fields.description')</label>
                            <textarea name="description" id="txtDescription" class="form-control" rows="3"></textarea>
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
    <script src="/assets/admin/vendor/cleave/addons/cleave-phone.tr.js"></script>
@endpush
