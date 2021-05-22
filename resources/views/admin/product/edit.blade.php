@extends('admin.layout.main')

@section('title', meta_title('tables.product.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.product.edit.put', $product) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.product.edit') [{{ $product->id }}]</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpName">@lang('fields.name')</label>
                            <input type="text" name="name" id="inpName" class="form-control" value="{{ $product->name }}">
                        </div>
                        <div class="form-group">
                            <label for="inpSlug">@lang('fields.slug')</label>
                            <input type="text" name="slug" id="inpSlug" class="form-control"
                                value="{{ $product->slug }}">
                        </div>
                        <div class="form-group">
                            <label for="txtContent">@lang('fields.content')</label>
                            <textarea name="content" id="txtContent" class="form-control" style="resize:none;height:150px;">{{ $product->content }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="inpPrice">@lang('fields.price')</label>
                            <input type="number" name="price" id="inpPrice" class="form-control"
                                value="{{ $product->price }}">
                        </div>
                        <div class="form-group">
                            <label for="inpMetaTitle">@lang('fields.meta.title')</label>
                            <input type="text" name="meta_title" id="inpMetaTitle" class="form-control"
                                value="{{ $product->meta_title }}">
                        </div>
                        <div class="form-group">
                            <label for="inpMetaKeywords">@lang('fields.meta.keywords')</label>
                            <input type="text" name="meta_keywords" id="inpMetaKeywords" class="form-control"
                                value="{{ $product->meta_keywords }}">
                        </div>
                        <div class="form-group">
                            <label for="txtMetaDescription">@lang('fields.meta.description')</label>
                            <textarea name="meta_description" id="txtMetaDescription" class="form-control" rows="3">{{ $product->meta_keywords }}</textarea>
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
