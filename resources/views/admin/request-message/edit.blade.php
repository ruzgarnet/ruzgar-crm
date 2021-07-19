@extends('admin.layout.main')

@section('title', meta_title('tables.request.message.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.request.message.edit.put', $requestMessage) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.request.message.add')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.request.messages') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.request.message.title')</a>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="form-group">
                            <label for="slcRole">@lang('fields.request_role')</label>
                            <select disabled name="role_id" id="slcRole" class="custom-select selectpicker">

                                @foreach ($roles as $role)
                                    <option @if ($requestMessage->role->id==$role->id) selected @else @endif value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtMessage">@lang('fields.message')</label>
                            <textarea disabled name="message" id="txtMessage" class="form-control"
                                rows="3">{{$requestMessage->message}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="slcStatus">@lang('fields.request_role')</label>
                            <select name="status" id="slcStatus" class="custom-select selectpicker">

                                @foreach(trans('tables.request.message.status') as $key => $status)
                                    <option @if ($requestMessage->status==$key) selected @else @endif value="{{ $key }}">{{ $status }}</option>
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
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
@endpush
