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
@endsection
