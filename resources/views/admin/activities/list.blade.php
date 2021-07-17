@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card list">

                <div class="card-header">
                    <h4>{{$customer->full_name}}</h4>
                </div>

                <div class="card-body">
                    <div class="activities">
                        @foreach ( $subscriptionCancels as $subscriptioncancel )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.subscriptioncancel') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$subscriptioncancel->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$subscriptioncancel->staff->full_name}}</a>

                                    </div>
                                    <p>{{$subscriptioncancel->description}}</p>
                                </div>
                            </div>
                        @endforeach

                        @foreach ( $subscriptionChanges as $subscriptionchange )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.subscriptionchange') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$subscriptionchange->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$subscriptionchange->staff->full_name}}</a>

                                    </div>
                                    <p>{{$subscriptionchange->description}}</p>
                                </div>
                            </div>
                        @endforeach

                        @foreach ( $subscriptionFreezes as $subscriptionfreeze )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.subscriptionfreeze') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$subscriptionfreeze->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$subscriptionfreeze->staff->full_name}}</a>

                                    </div>
                                    <p>{{$subscriptionfreeze->description}}</p>

                                    @if ($subscriptionfreeze!=Null)
                                    <div class="mb-2">

                                        <p>Aboneliği yeniden açan kullanıcı: <b>{{$subscriptionfreeze->unfreezeStaff->full_name}}</b>  {{$subscriptionfreeze->unfreezed_at}}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @foreach ( $subscriptionPrizeEdits as $subscriptionprizeedit )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.subscriptionpriceedit') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$subscriptionprizeedit->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$subscriptionprizeedit->staff->full_name}}</a>

                                    </div>
                                    <p>{{$subscriptionprizeedit->description}}</p>
                                    <div class="mb-2">

                                        <p><b>Eski Ücret:</b> {{print_money($subscriptionprizeedit->old_price)}} <b>Yeni Ücret:</b> {{print_money($subscriptionprizeedit->new_price)}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="activities">
                        @foreach ( $paymentCancels as $paymentcancel )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.paymentcancel') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$paymentcancel->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$paymentcancel->staff->full_name}}</a>

                                    </div>
                                    <p>{{$paymentcancel->description}}</p>
                                </div>
                            </div>
                        @endforeach

                        @foreach ( $paymentPriceEdits as $paymentprizeedit )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.paymentpriceedit') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$paymentprizeedit->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$paymentprizeedit->staff->full_name}}</a>

                                    </div>
                                    <p>{{$paymentprizeedit->description}}</p>
                                    <div class="mb-2">

                                        <p><b>Eski Ücret:</b> {{print_money($paymentprizeedit->old_price)}} <b>Yeni Ücret:</b> {{print_money($paymentprizeedit->new_price)}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach ( $paymentCreates as $paymentcreate )

                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.paymentcreate') </h4>
                                    <div class="mb-2">
                                        <span class="text-job text-primary">{{$paymentcreate->created_at}}</span>
                                        <span class="bullet"></span>
                                        <a class="text-job" >İşlemi Yapan Kullanıcı : {{$paymentcreate->staff->full_name}}</a>
                                    </div>
                                    <p>{{$paymentcreate->description}}</p>
                                    <div class="mb-2">

                                        <p><b>Ücret:</b> {{print_money($paymentcreate->price)}} <b> Tarih:</b> {{($paymentcreate->date)}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach ( $paymentDeletes as $paymentdelete )
                            <div class="activity">
                                <div class="activity-icon bg-primary text-white shadow-primary">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div class="activity-detail">
                                    <h4>@lang('titles.paymentdelete') </h4>
                                    <div class="mb-2">
                                    <span class="text-job text-primary">{{$paymentdelete->created_at}}</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" >İşlemi Yapan Kullanıcı : {{$paymentdelete->staff->full_name}}</a>

                                    </div>
                                    <p>{{$paymentdelete->paymentdelete}}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
