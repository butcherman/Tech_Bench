@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Customers</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customers</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <customer-list
                sys_types="{{json_encode($sysTypes)}}"
                get_cust_route="{{route('customer.search')}}"
                new_cust_route="{{route('customer.id.create')}}"
                
            ></customer-list>
        </div>
    </div>
</div>
@endsection