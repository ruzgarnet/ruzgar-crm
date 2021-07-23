@extends('admin.layout.main')

@section('content')
    <div class="section-header">
        <h1>Anasayfa</h1>
    </div>

    <div class="row">
        @if (request()->user()->username == "engin" || request()->user()->username == "admin")
            <div class="col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Müşteri Sayısı</h4>
                        </div>
                        <div class="card-body">
                            {{ $total['customer']}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Abonelik Sayısı</h4>
                        </div>
                        <div class="card-body">
                            {{ $total['subscription'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-toolbox"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Aktif Arıza Kaydı</h4>
                    </div>
                    <div class="card-body">
                        {{ $total['faultRecord'] }}
                    </div>
                </div>
            </div>
        </div>

        @if (request()->user()->username == "admin" || request()->user()->username == "engin")
            <div class="col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-lira-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Bu Ayın Tahsilatı</h4>
                        </div>
                        <div class="card-body">
                            {{ print_money($total['payment']) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-lg-12">
            <div class="card list">
                <div class="card-header">
                    <h4>Son Eklenen Abonelikler</h4>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('fields.customer')</th>
                                    <th scope="col">@lang('fields.service')</th>
                                    <th scope="col">@lang('fields.price')</th>
                                    <th scope="col">@lang('fields.start_date')</th>
                                    <th scope="col">@lang('fields.end_date')</th>
                                    <th scope="col">@lang('fields.approve_date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr data-id="{{ $subscription->id }}"
                                        class="{{ $subscription->approved_at == null ? 'un-approved-row' : 'approved-row' }}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <a
                                                href="{{ route('admin.customer.show', $subscription->customer_id) }}">{{ $subscription->customer->full_name }}</a>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $subscription->service->name }}
                                                @if ($subscription->isCanceled())
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="popover" data-html="true"
                                                        data-content="<b>Tarih:</b> {{ convert_date($subscription->cancellation->created_at, 'large') }} <br>
                                                                                                        <b>Personel</b>: {{ $subscription->cancellation->staff->full_name }} <br>
                                                                                                        <b>Sebep</b>: {{ $subscription->cancellation->description }}">
                                                        @lang('titles.cancel')
                                                    </button>
                                                @endif
                                            </div>
                                            @if ($subscription->isChanged())
                                                <div>
                                                    <small>
                                                        <a
                                                            href="{{ route('admin.subscription.payments', $subscription->getChanged()) }}">
                                                            {{ $subscription->getChanged()->service->name }}
                                                        </a>
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $subscription->price_print }}</td>
                                        <td>{{ convert_date($subscription->start_date, 'mask') }}</td>
                                        <td>
                                            @if ($subscription->end_date)
                                                {{ convert_date($subscription->end_date, 'mask') }}
                                            @else
                                                <span class="badge badge-primary">@lang('fields.commitless')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($subscription->approved_at != null)
                                                {{ convert_date($subscription->approved_at, 'mask_time') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
