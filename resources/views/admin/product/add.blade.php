@extends('admin.layout.main')

@section('title', meta_title('tables.product.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.product.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.product.add')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpName">@lang('fields.name')</label>
                            <input type="text" name="name" id="inpName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inpSlug">@lang('fields.slug')</label>
                            <input type="text" name="slug" id="inpSlug" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inpPrice">@lang('fields.price')</label>
                            <input type="text" name="price" id="inpPrice" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="txtContent">@lang('fields.content')</label>
                            <textarea name="content" id="txtContent" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inpMetaTitle">@lang('fields.meta.title')</label>
                            <input type="text" name="meta_title" id="inpMetaTitle" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inpMetaKeywords">@lang('fields.meta.keywords')</label>
                            <input type="text" name="meta_keywords" id="inpMetaKeywords" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="txtMetaDescription">@lang('fields.meta.description')</label>
                            <textarea name="meta_description" id="txtMetaDescription" class="form-control" rows="3"></textarea>
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
