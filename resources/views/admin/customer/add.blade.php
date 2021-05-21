@extends('admin.layout.main')

@section('title', meta_title('tables.customer.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.customer.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.customer.add')</h4>
                    </div>
                    <div class="card-body">
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
                            <label for="inpIdentificationNumber">@lang('fields.identification_number')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                </div>
                                <input type="text" name="identification_number" id="inpIdentificationNumber"
                                    class="form-control identification-mask">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('fields.gender')</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="gender" id="radGenderMale" class="custom-control-input"
                                        value="1">
                                    <label class="custom-control-label" for="radGenderMale">@lang('fields.male')</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="gender" id="radGenderFemale" class="custom-control-input"
                                        value="2">
                                    <label class="custom-control-label" for="radGenderFemale">@lang('fields.female')</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpBirthday">@lang('fields.birthday')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="birthday" id="inpBirthday" class="form-control date-mask">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
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
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpSecondaryTelephone">@lang('fields.secondary_telephone')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="secondary_telephone" id="inpSecondaryTelephone"
                                            class="form-control telephone-mask">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpEmail">@lang('fields.email')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </div>
                                </div>
                                <input type="email" name="email" id="inpEmail" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="slcCity">@lang('fields.city')</label>
                                    <select name="city_id" id="slcCity" class="custom-select">
                                        <option disabled selected>@lang('tables.city.select')</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="slcDistrict">@lang('fields.district')</label>
                                    <select name="district_id" id="slcDistrict" class="custom-select" disabled>
                                        <option disabled selected>@lang('tables.city.select')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtAddress">@lang('fields.address')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <textarea name="address" id="txtAddress" class="form-control"></textarea>
                            </div>
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

@push('script')
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
    <script src="/assets/admin/vendor/cleave/addons/cleave-phone.tr.js"></script>
@endpush
