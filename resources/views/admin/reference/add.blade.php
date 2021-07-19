@extends('admin.layout.main')

@section('title', meta_title('tables.reference.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.reference.add.post', $subscription) }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.reference.add')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcReference">@lang('fields.reference')</label>
                            <select id="slcReference" class="custom-select" disabled>
                                <option selected>{{ $subscription->select_print }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slcSubscription">@lang('fields.referenced')</label>
                            <select name="subscription_id" id="slcSubscription" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.subscription.select')</option>
                                @foreach ($subscriptions as $row)
                                    @if ($row->id != $subscription->id)
                                        <option value="{{ $row->id }}">{{ $row->reference_print }}</option>
                                    @endif
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
    <script src="/assets/admin/vendor/ckeditor/ckeditor.js"></script>
    <script src="/assets/admin/vendor/slugify/slugify.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/assets/admin/vendor/cleave/cleave.min.js"></script>
@endpush
