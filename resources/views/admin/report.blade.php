@extends('admin.layout.main')

@section('title', meta_title('fields.report'))

@section('content')
    <div class="section-header">
        <h1>Rüzgar CRM Aylık Rapor [{{ convert_date($date, 'month_period') }}]</h1>

        <form method="POST" action="{{ route('admin.report') }}" data-ajax="false" class="report card-header-buttons ml-auto">
            @csrf
            <input type="date" name="date" name="dtDate" class="form-control" value="{{ $date ?? date('Y-m-15') }}">
            <button type="submit" class="btn btn-primary">Listele</button>
        </form>
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
