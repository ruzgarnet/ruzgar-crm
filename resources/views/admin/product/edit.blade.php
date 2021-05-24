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

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.products') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.product.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcCategory">@lang('fields.category')</label>
                            <select name="category_id" id="slcCategory" class="custom-select selectpicker">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($product->category_id === $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpName">@lang('fields.name')</label>
                                    <input type="text" name="name" id="inpName" class="form-control slug-to-input"
                                        data-slug="inpSlug" value="{{ $product->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpSlug">@lang('fields.slug')</label>
                                    <input type="text" name="slug" id="inpSlug" class="form-control slug-input"
                                        value="{{ $product->slug }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpModel">@lang('fields.model')</label>
                                    <input type="text" name="model" id="inpModel" class="form-control slug-input" data-lower="off"
                                        value="{{ $product->model }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpPrice">@lang('fields.price')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">₺</div>
                                        </div>
                                        <input type="number" name="price" id="inpPrice" class="form-control money-input" min="0"
                                            step=".01" value="{{ $product->price }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtContent">@lang('fields.content')</label>
                            <textarea name="content" id="txtContent" rows="3"
                                class="form-control txt-editor">{!! $product->content !!}</textarea>
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
                            <textarea name="meta_description" id="txtMetaDescription" class="form-control"
                                rows="3">{{ $product->meta_description }}</textarea>
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
    <script src="/assets/admin/vendor/ckeditor/ckeditor.js"></script>
    <script src="/assets/admin/vendor/slugify/slugify.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
@endpush
