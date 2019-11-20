@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <customer-details :cust_details="{{$details}}" :is_fav="{{$isFav}}" :can_del="{{$canDel}}"></customer-details>
    </div>
</div>
<div class="row">
    <div class="col-md-5" class="grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Customer Systems:</div>
            <div class="card-body">
                <customer-systems cust_id="{{$cust_id}}"></customer-systems>
            </div>
        </div>
    </div>
    <div class="col-md-7" class="grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Customer Contacts:</div>
            <div class="card-body">
                <p>customer details</p>
            </div>
        </div>
    </div>
</div>
@endsection
