@extends('admin.layout.main')

@section('title', meta_title('tables.subscription.change_service'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.subscription.change.put', $subscription) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.subscription.change_service') [{{ $subscription->id }}]</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.subscriptions') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.subscription.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpSubscription">@lang('fields.subscription')</label>
                            <input type="text" id="inpSubscription" class="form-control" readonly
                                value="{{ $subscription->select_print }}">
                        </div>

                        <div class="form-group">
                            <label for="slcService">@lang('fields.service')</label>
                            <select name="service_id" id="slcService" class="custom-select service-select selectpicker"
                                v-model="service" v-selectpicker="service" v-on:change="changeService()">
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" @if ($subscription->service_id == $service->id) selected disabled @endif>
                                        {{ $service->select_print }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inpDate">@lang('fields.date')</label>
                            <input type="text" name="date" id="inpDate" class="form-control date-mask"
                                value="{{ convert_date(date('Y-m-d'), 'mask') }}">
                        </div>

                        <div class="form-group">
                            <label for="inpPrice">@lang('fields.price')</label>
                            <input type="number" name="price" id="inpPrice" class="form-control" step="0.01">
                        </div>

                        <div class="form-group">
                            <label for="inpPayment">@lang('fields.change_service_payment')</label>
                            <input type="number" name="payment" id="inpPayment" class="form-control" step="0.01" value="0.00">
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
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
@endpush
