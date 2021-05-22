@extends('admin.layout.main')

@section('title', meta_title('tables.category.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.category.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.category.add')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcContractType">@lang('fields.contract_type')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                </div>
                                <select name="contract_type_id" id="slcContractType" class="custom-select">
                                    <option disabled selected>@lang('tables.contract_type.select')</option>
                                    @foreach ($contractTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="slcType">@lang('fields.data_type')</label>
                            <select name="type" id="slcType" class="custom-select">
                                <option disabled selected>@lang('tables.category.select_type')</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}">@lang("tables.category.types.{$type}")</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slcParentCategory">@lang('fields.parent_category')</label>
                            <select name="parent_id" id="slcParentCategory" class="custom-select selectpicker">
                                <option value="" selected>@lang('fields.none')</option>
                                @foreach ($categories as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inpKey">@lang('fields.key')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                <input type="text" name="key" id="inpKey" class="form-control slug-input">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpName">@lang('fields.name')</label>
                            <input type="text" name="name" id="inpName" class="form-control slug-to-input" data-slug="inpSlug">
                        </div>
                        <div class="form-group">
                            <label for="inpSlug">@lang('fields.slug')</label>
                            <input type="text" name="slug" id="inpSlug" class="form-control slug-input">
                        </div>
                        <div class="form-group">
                            <label>@lang('fields.content')</label>
                            <textarea name="content" id="txtContent" class="form-control txt-editor" rows="3"></textarea>
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
                            <textarea name="meta_description" id="txtMetaDescription" rows="3" class="form-control"></textarea>
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
@endpush
