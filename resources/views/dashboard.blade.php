@extends('layouts.app')

@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">My Dashboard</li>
</ol>
@endsection

@section('content')
    <div class="row justify-content-center pad-top">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Welcome {{ Auth::user()->first_name.' '.Auth::user()->last_name }}</h1></div>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-8">
            <notifications notification_route="{{route('get-notifications')}}" dismiss_route="{{route('del-notification', ':id')}}"></notifications>
        </div>
    </div>
    @if(count($modules) != 0)
    <div class="row justify-content-center pad-top">
        <div class="col-12">
            <div class="card">
            <div class="card-header"><h4>Tools and Extras:</h4></div>
            <div class="card-body">
                <div class="row">
                   @foreach($modules as $mod)         
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card text-white o-hidden h-100 bookmark-card">
                                <a href="{{route('index').'/'.$mod->getLowerName()}}" class="card-body text-white">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-link"></i>
                                    </div>
                                    <div class="mr-5">{{preg_replace('/(.*?[a-z]{1})([A-Z]{1}.*?)/', '${1} ${2}', $mod->getName())}}</div>
                                </a>
                            </div>
                        </div>
                    @endforeach        
                </div>
            </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Customer Favorites</h4></div>
                <div class="card-body">
                    <div class="row">
                        @if(!$custFavs->isEmpty())
                            @foreach($custFavs as $fav)
                                <div class="col-xl-3 col-sm-6 mb-3">
                                    <div class="card text-white o-hidden h-100 bookmark-card">
                                        <a href="{{route('customer.details', [$fav->cust_id, urlencode($fav->name)])}}" class="card-body text-white">
                                            <div class="card-body-icon">
                                                <i class="fa fa-fw fa-users"></i>
                                            </div>
                                            <div class="mr-5">{{$fav->name}}</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12"><h3 class="text-center">No Customer Favorites</h3></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
