@extends('admin.layout.main')

@section('title', meta_title('tables.staff.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.staff.edit.put', $staff) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.staff.edit') [{{ $staff->id }}]</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.staffs') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.staff.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcDealer">@lang('fields.dealer')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                                <select name="dealer_id" id="slcDealer" class="custom-select">
                                    @foreach ($dealers as $dealer)
                                        <option value="{{ $dealer->id }}" @if ($dealer->id == $staff->dealer_id) selected @endif>
                                            {{ $dealer->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    class="form-control identification-mask" value="{{ $staff->identification_number }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpFirstName">@lang('fields.first_name')</label>
                                    <input type="text" name="first_name" id="inpFirstName" class="form-control"
                                        value="{{ $staff->first_name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpLastName">@lang('fields.last_name')</label>
                                    <input type="text" name="last_name" id="inpLastName" class="form-control"
                                        value="{{ $staff->last_name }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('fields.gender')</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="gender" id="radGenderMale" class="custom-control-input"
                                        value="1" @if ($staff->gender == 1) checked @endif>

                                    <label class="custom-control-label" for="radGenderMale">@lang('fields.male')</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="gender" id="radGenderFemale" class="custom-control-input"
                                        value="2" @if ($staff->gender == 2) checked @endif>

                                    <label class="custom-control-label" for="radGenderFemale">@lang('fields.female')</label>
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
                                <input type="text" name="telephone" id="inpTelephone" class="form-control telephone-mask"
                                    value="0{{ $staff->telephone }}">
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
                                <input type="email" name="email" id="inpEmail" class="form-control"
                                    value="{{ $staff->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpSecondaryTelephone">@lang('fields.secondary_telephone')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </div>
                                <input type="text" name="secondary_telephone" id="inpSecondaryTelephone"
                                    class="form-control telephone-mask" value="0{{ $staff->secondary_telephone }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpBirthday">@lang('fields.birthday')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-birthday-cake"></i>
                                    </div>
                                </div>
                                <input type="text" name="birthday" id="inpBirthday" class="form-control date-mask"
                                    value="{{ convert_date($staff->birthday, 'mask') }}">
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
                                <textarea name="address" id="txtAddress"
                                    class="form-control">{{ $staff->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpStartedAt">@lang('fields.started_at')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="started_at" id="inpStartedAt" class="form-control date-mask"
                                    value="{{ convert_date($staff->started_at, 'mask') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpReleasedAt">@lang('fields.released_at')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="released_at" id="inpReleasedAt" class="form-control date-mask"
                                    value="{{ $staff->released_at ? convert_date($staff->released_at, 'mask') : '' }}">
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
