@extends('admin.layout.main')

@section('title', meta_title('tables.role.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.role.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.role.add')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.roles') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.role.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inpName">@lang('fields.name')</label>
                            <input type="text" name="name" id="inpName" class="form-control">
                        </div>

                        <div class="row">
                            @foreach ($abilities as $groupKey => $group)
                                <div class="col-lg-6">
                                    <div class="ability-group">
                                        <h6>@lang("tables.{$groupKey}.title")</h6>
                                        <div class="ability-checkboxes">
                                            @foreach ($group as $ability)
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="abilities[]"
                                                        value="{{ $ability->key }}" id="chkAbility{{ $ability->id }}">
                                                    <label class="custom-control-label"
                                                        for="chkAbility{{ $ability->id }}">@lang("tables.{$ability->name}")</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
