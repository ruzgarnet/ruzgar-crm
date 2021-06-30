@extends('admin.layout.main')

@section('title', meta_title('tables.customer_application_type.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.customer.application.type.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.customer_application_type.add')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.customer.application.types') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.customer_application_type.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpTitle">@lang('fields.title')</label>
                            <input type="text" name="title" id="inpTitle" class="form-control">
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
