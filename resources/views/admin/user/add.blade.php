@extends('admin.layout.main')

@section('title', meta_title('tables.user.add'))

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ relative_route('admin.user.add.post') }}">
                <div class="card form">
                    <div class="card-header">
                        <h4>@lang('tables.user.add')</h4>

                        <div class="card-header-buttons">
                            <a href="{{ route('admin.users') }}" class="btn btn-primary"><i
                                    class="fas fa-sm fa-list-ul"></i> @lang('tables.user.title')</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slcStaff">@lang('fields.staff')</label>
                            <select name="staff_id" id="slcStaff" class="custom-select selectpicker">
                                <option selected disabled>@lang('tables.staff.select')</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slcRole">@lang('fields.role')</label>
                            <select name="role_id" id="slcRole" class="custom-select selectpicker">
                                <option selected disabled value="0">@lang('tables.role.select')</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inpUsername">@lang('fields.username')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <input type="text" name="username" id="inpUsername" class="form-control slug-input"
                                    data-lower="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpEmail">@lang('fields.email')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </div>
                                </div>
                                <input type="email" name="email" id="inpEmail" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inpPassword">@lang('fields.password')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                <input type="password" name="password" id="inpPassword" class="form-control">
                            </div>
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
    <script src="/assets/admin/vendor/slugify/slugify.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
@endpush
