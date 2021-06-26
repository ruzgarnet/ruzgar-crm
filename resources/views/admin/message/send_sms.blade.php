@extends('admin.layout.main')

@section('title', meta_title('tables.message.send_sms'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form id="messageForm" method="POST" action="{{ relative_route('admin.message.send.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.message.send_sms')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.messages') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.message.title')</a>
                                    <a href="{{ route('admin.message.add') }}" class="btn btn-primary"><i
                                        class="fas fa-sm fa-plus"></i> @lang('tables.message.add')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcType">@lang('fields.select_send_type')</label>
                            <select name="type" id="slcType" class="custom-select selectpicker">
                                <option disabled selected>@lang('tables.customer.select')</option>
                                @foreach (trans("tables.message.selects") as $key => $select)
                                    <option value="{{ $key }}">{{ $select }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group message-types message-type-1">
                            <label for="slcCustomer">@lang('fields.customer')</label>
                            <select name="customers[]" id="slcCustomer" class="custom-select selectpicker" multiple>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group message-types message-type-2">
                            <label for="slcCategory">@lang('fields.category')</label>
                            <select name="category_id" id="slcCategory" class="custom-select selectpicker">
                                <option disabled selected>@lang('tables.category.select')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slcMessage">@lang('fields.message')</label>
                            <select name="message_id" id="slcMessage" class="custom-select selectpicker">
                                <option disabled selected>@lang('tables.message.select')</option>
                                @foreach ($messages as $message)
                                    <option value="{{ $message->id }}">{{ $message->title }}</option>
                                @endforeach
                            </select>
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
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
    <script src="/assets/admin/vendor/cleave/addons/cleave-phone.tr.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
@endpush
