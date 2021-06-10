@extends('admin.layout.main')

@section('title', meta_title('tables.infrastructure.title'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" id="infrastructure_statu_form" onsubmit="return processForm()" autocomplete="off">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.infrastructure.title')</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="cities">@lang('fields.city')</label>
                                    <select name="cities" id="cities" class="custom-select">

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="districts">@lang('fields.district')</label>
                                    <select name="districts" id="districts" class="custom-select">
                                        <option disabled selected>@lang('tables.district.select')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="townships">@lang('fields.townships')</label>
                                    <select name="townships" id="townships" class="custom-select">
                                        <option disabled selected>@lang('tables.infrastructure.townships')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="villages">@lang('fields.villages')</label>
                                    <select name="villages" id="villages" class="custom-select">
                                        <option disabled selected>@lang('tables.infrastructure.villages')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="neighborhoods">@lang('fields.neighborhoods')</label>
                                    <select name="neighborhoods" id="neighborhoods" class="custom-select">
                                        <option disabled selected>@lang('tables.infrastructure.neighborhoods')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="streets">@lang('fields.streets')</label>
                                    <select name="streets" id="streets" class="custom-select">
                                        <option disabled selected>@lang('tables.infrastructure.streets')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="buildings">@lang('fields.buildings')</label>
                                    <select name="buildings" id="buildings" class="custom-select">
                                        <option disabled selected>@lang('tables.infrastructure.buildings')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="doors">@lang('fields.doors')</label>
                                    <select name="doors" id="doors" class="custom-select">
                                        <option disabled selected>@lang('tables.infrastructure.doors')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="inpFullName">@lang('fields.full_name')</label>
                                    <input type="text" maxlength="30" minlength="10" name="full_name" id="inpFullName"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="inpTelephone">@lang('fields.telephone')</label>
                                    <input type="text" name="telephone" id="inpTelephone" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">@lang('fields.send')</button>
                    </div>
                    <div class="d-flex flex-column my-2 w-100 align-items-center">
                        <p id="speed_information" style="font-size:20px;display:none;">Binanızda port bulunmaktadır.</p>
                        <p id="speed" style="font-size:20px;display:none;">Alabileceğiniz Maksimum Hız : 16 Mbps</p>
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
    <script src="/assets/js/tt_api.js"></script>
@endpush
