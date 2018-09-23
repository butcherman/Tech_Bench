@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pad-top">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Welcome {{ $current_user->first_name.' '.$current_user->last_name }}</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Customer Favorites</h4></div>
                <div class="card-body">
                    <div class="row">
                        @if(!$custFavs->isEmpty())
                            @foreach($custFavs as $fav)
                                <div class="col-xl-3 col-sm-6 mb-3">
                                    <div class="card text-white bg-info o-hidden h-100">
                                        <a href="{{route('customer.details', [$fav->cust_id, urlencode($fav->name)])}}" class="card-body text-white">
                                            <div class="card-body-icon">
                                                <i class="fa fa-fw fa-user"></i>
                                            </div>
                                            <div class="mr-5">{{$fav->name}}</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12"><h3 class="text-center">No Favorites</h3></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Tech Tip Favorites</h4></div>
                <div class="card-body">
                    <div class="row">
                        @if(!$tipFavs->isEmpty())
                            @foreach($tipFavs as $fav)
                                <div class="col-xl-3 col-sm-6 mb-3">
                                    <div class="card text-white bg-info o-hidden h-100">
                                        <a href="{{route('customer.details', [$fav->tip_id, urlencode($fav->subject)])}}" class="card-body text-white">
                                            <div class="card-body-icon">
                                                <i class="fa fa-fw fa-user"></i>
                                            </div>
                                            <div class="mr-5">{{$fav->subject}}</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12"><h3 class="text-center">No Favorites</h3></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
