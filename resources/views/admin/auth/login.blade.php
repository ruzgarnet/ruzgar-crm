@extends('admin.layout.auth')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h4>@lang('auth.login')</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.login.post') }}" data-ajax="false">
                @csrf
                <div class="form-group">
                    <label for="inputUsername">@lang('fields.username')</label>
                    <input type="text" name="username" id="inputUsername"
                        class="form-control @error('username') is-invalid @enderror" autofocus value="{{ old('username') }}">
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="control-label">@lang('fields.password')</label>
                    <input name="password" id="inputPassword" type="password"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        @lang('auth.login')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
