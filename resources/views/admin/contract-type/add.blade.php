@extends('admin.layout.main')

@section('title', meta_title('tables.contract_type.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.contract.type.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.contract_type.add')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.contract.types') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.contract_type.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpKey">@lang('fields.key')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                <input type="text" name="key" id="inpKey" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpTitle">@lang('fields.title')</label>
                            <input type="text" name="title" id="inpTitle" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inpView">@lang('fields.view')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                </div>
                                <input type="text" name="view" id="inpView" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpOptions">@lang('fields.options')</label>
                            <textarea name="options" id="inpOptions" class="form-control" rows="3"></textarea>
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
