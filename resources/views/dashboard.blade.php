@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <h4>Welcome {{Auth::user()->first_name.' '.Auth::user()->last_name}}</h4>
    </div>
</div>
@if(session()->has('status'))
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="alert alert-primary text-center"><h4>{{session()->get('status')}}</h4></div>
    </div>
</div>
@endif
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Notifications</h4>
                <notification-dashboard></notification-dashboard>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin d-md-flex d-none pr-0">
        <div class="row w-100">
            <div class="col-12 pr-0">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Tech Tips:</p>
                        <h3>{{$tips30}} New Tips</h3>
                        <p class="text-small">(Last 30 Days)</p>
                        <h4>{{$tipsAll}} Total Tips</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 pr-0">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="card-title">File Links:</p>
                        <h3>{{$activeLinks}} Active File Links</h3>
                        <h4>{{$totalLinks}} Total File Links</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Customer Favorites</h4>
                <div class="row">
                @foreach($custFavs as $fav)
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white o-hidden h-100 bookmark-card overflow-hidden">
                            <a href="{{route('customer.details', [$fav->cust_id, $fav->Customers->name])}}" class="card-body text-white">
                                <div class="card-body-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="mr-5">{{$fav->Customers->name}}</div>
                            </a>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tech Tip Favorites</h4>
                <div class="row">
                    @foreach($tipFavs as $tip)
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white o-hidden h-100 bookmark-card overflow-hidden">
                            <a href="{{route('tip.details', [$tip->tip_id, urlencode($tip->TechTips->subject)])}}" class="card-body text-white">
                                <div class="card-body-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="mr-5">{{$tip->TechTips->subject}}</div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
