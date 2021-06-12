@extends('admin.layout.main')

@section('title', meta_title('tables.dealer.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.dealer.edit.put', $dealer) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.dealer.edit') [{{ $dealer->id }}]</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.dealers') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.dealer.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpName">@lang('fields.name')</label>
                            <input type="text" name="name" id="inpName" class="form-control" value="{{ $dealer->name }}">
                        </div>
                        <div class="form-group">
                            <label for="inpTaxNumber">@lang('fields.tax_number')</label>
                            <input type="text" name="tax_number" id="inpTaxNumber" class="form-control"
                                value="{{ $dealer->tax_number }}">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="slcCity">@lang('fields.city')</label>
                                    <select name="city_id" id="slcCity" class="custom-select">
                                        <option disabled selected>@lang('tables.city.select')</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @if ($dealer->city_id == $city->id) selected @endif>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="slcDistrict">@lang('fields.district')</label>
                                    <select name="district_id" id="slcDistrict" class="custom-select">
                                        @foreach ($dealer->city->districts as $district)
                                            <option value="{{ $district->id }}" @if ($dealer->district_id == $district->id) selected @endif>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
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
                                <textarea name="address" id="txtAddress"
                                    class="form-control">{{ $dealer->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpTelephone">@lang('fields.telephone')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </div>
                                <input type="text" name="telephone" id="inpTelephone" class="form-control telephone-mask" value="0{{ $dealer->telephone }}">
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
                                <input type="text" name="started_at" id="inpStartedAt" class="form-control date-mask" value="{{ convert_date($dealer->started_at, 'mask') }}">
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
