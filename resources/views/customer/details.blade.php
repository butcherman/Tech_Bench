@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <customer-details
            :cust_details="{{$details}}"
            :is_fav="{{$isFav}}"
        @can('hasAccess', 'deactivate_customer')
            :can_del="true"
        @endcan
            parent="{{$parent}}"
        >
        </customer-details>
    </div>
</div>
<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Customer Equipiment:</div>
            <div class="card-body">
                <customer-systems cust_id="{{$cust_id}}" :linked="{{$linked}}"></customer-systems>
            </div>
        </div>
    </div>
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Customer Contacts:</div>
            <div class="card-body">
                <customer-contacts cust_id="{{$cust_id}}" :phone_types="{{json_encode($numberTypes)}}" :linked="{{$linked}}"></customer-contacts>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Customer Notes:</div>
            <div class="card-body">
                <customer-notes cust_id="{{$cust_id}}" :linked="{{$linked}}"></customer-notes>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Customer Files:</div>
            <div class="card-body">
                <customer-files cust_id="{{$cust_id}}" :file_types="{{json_encode($fileTypes)}}" :linked="{{$linked}}"></customer-files>
            </div>
        </div>
    </div>
</div>
@endsection
