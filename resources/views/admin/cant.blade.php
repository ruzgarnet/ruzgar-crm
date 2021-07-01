@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('titles.no_permission')</h4>
                </div>
                <div class="card-body">
                    <div class="empty-state" data-height="300">
                        <div class="empty-state-icon">
                            <i class="fas fa-question"></i>
                        </div>
                        <h2>@lang('titles.no_permission')</h2>
                        <p class="lead">@lang('warnings.no_permission')</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-4">@lang('titles.return_home')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
