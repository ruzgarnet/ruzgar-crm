@extends('admin.layout.main')

@section('title', meta_title('tables.message.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.message.edit.put', $message) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.message.edit') [{{ $message->id }}]</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpKey">@lang('fields.key')</label>
                            <input type="text" name="key" id="inpKey" class="form-control" value="{{ $message->key }}">
                        </div>
                        <div class="form-group">
                            <label for="inpTitle">@lang('fields.title')</label>
                            <input type="text" name="title" id="inpTitle" class="form-control"
                                value="{{ $message->title }}">
                        </div>
                        <div class="form-group">
                            <label for="txtMessage">@lang('fields.message')</label>
                            <textarea name="message" id="txtMessage" class="form-control" style="resize:none;height:150px;">{{ $message->message }}</textarea>
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
