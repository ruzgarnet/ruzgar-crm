@extends('admin.layout.main')

@section('title', meta_title('tables.service.edit'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.service.edit.put', $service) }}">
                @method('put')
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.service.edit') [{{ $service->id }}]</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.services') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.service.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcCategory">@lang('fields.category')</label>
                            <select name="category_id" id="slcCategory" class="custom-select selectpicker">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($service->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpName">@lang('fields.name')</label>
                                    <input type="text" name="name" id="inpName" class="form-control slug-to-input"
                                        data-slug="inpSlug" value="{{ $service->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpSlug">@lang('fields.slug')</label>
                                    <input type="text" name="slug" id="inpSlug" class="form-control slug-input"
                                        value="{{ $service->slug }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpModel">@lang('fields.model')</label>
                                    <input type="text" name="model" id="inpModel" class="form-control slug-input" data-lower="off"
                                        value="{{ $service->model }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpOriginalPrice">@lang('fields.original_price')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">₺</div>
                                        </div>
                                        <input type="number" name="original_price" id="inpOriginalPrice" class="form-control money-input"
                                            min="0" step=".01" value="{{ $service->original_price }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpPrice">@lang('fields.price')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">₺</div>
                                        </div>
                                        <input type="number" name="price" id="inpPrice" class="form-control money-input" min="0"
                                            step=".01" value="{{ $service->price }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-primary">@lang('fields.campaing')</h4>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="slcOptionsCommitment">@lang('fields.commitment')</label>
                                    <select name="options[commitment]" id="slcOptionsCommitment" class="custom-select">
                                        <option value="" @if (!$service->getOption('commitment')) selected @endif>@lang('fields.commitment_period')</option>
                                        @foreach (trans('fields.commitments') as $key => $commitment)
                                            <option value="{{ $key }}" @if ($service->getOption('commitment') == $key) selected @endif>{{ $commitment }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpOptionsPrice">@lang('fields.campaing_price')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">₺</div>
                                        </div>
                                        <input type="number" name="options[price]" id="inpOptionsPrice" class="form-control money-input" min="0" step=".01" value="{{ $service->getOption('price') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="inpOptionsDuration">@lang('fields.campaing_duration')</label>
                                    <div class="input-group">
                                        <input type="number" name="options[duration]" id="inpOptionsDuration" class="form-control money-input" min="0" step="1" value="{{ $service->getOption('duration') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpDownload">@lang('fields.download')</label>
                                    <input type="number" step="0.01" name="download" id="inpDownload" class="form-control" value="{{ $service->download }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inpUpload">@lang('fields.upload')</label>
                                    <input type="number" step="0.01" name="upload" id="inpUpload" class="form-control" value="{{ $service->upload }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtContent">@lang('fields.content')</label>
                            <textarea name="content" id="txtContent" rows="3"
                                class="form-control txt-editor">{!! $service->content !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="inpMetaTitle">@lang('fields.meta.title')</label>
                            <input type="text" name="meta_title" id="inpMetaTitle" class="form-control"
                                value="{{ $service->meta_title }}">
                        </div>
                        <div class="form-group">
                            <label for="inpMetaKeywords">@lang('fields.meta.keywords')</label>
                            <input type="text" name="meta_keywords" id="inpMetaKeywords" class="form-control"
                                value="{{ $service->meta_keywords }}">
                        </div>
                        <div class="form-group">
                            <label for="txtMetaDescription">@lang('fields.meta.description')</label>
                            <textarea name="meta_description" id="txtMetaDescription" class="form-control"
                                rows="3">{{ $service->meta_description }}</textarea>
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
