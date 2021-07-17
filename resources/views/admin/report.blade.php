@extends('admin.layout.main')

@section('content')
    <div class="section-header">
        <h1>Rapor</h1>
    </div>

    <div class="row">
        <x-admin.report-table :reports="$reports" />

        @foreach ($categories as $key => $category)
            <div class="col-12">
                <div class="section-header">
                    <h1>{{ $key }}</h1>
                </div>
            </div>
            <x-admin.report-table :reports="$category" :categoryKey="$key" />
        @endforeach
    </div>
@endsection

@push('style')
    <style>
        table td {
            width: 25%;
        }
    </style>
@endpush
