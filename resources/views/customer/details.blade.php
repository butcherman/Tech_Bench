@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customers</a></li>
    <li class="breadcrumb-item active">{{$details->name}}</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12"> 
        <customer-details
            cust_id="{{$details->cust_id}}"
            is_fav="{{$isFav}}"
            fav_route="{{route('customer.toggleFav', [':action', $details->cust_id])}}"
            show_route="{{route('customer.id.show', $details->cust_id)}}"
            edit_route="{{route('customer.id.update', $details->cust_id)}}"
        ></customer-details>
    </div>
</div>

@endsection
