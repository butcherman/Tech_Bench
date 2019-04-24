@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customers</a></li>
    <li class="breadcrumb-item active">New Customer</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>New Customer</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        <new-customer-form
            check_id_route="{{route('customer.checkID', ':id')}}"
            submit_route="{{route('customer.id.store')}}"
        ></new-customer-form>
    </div>
</div>
@endsection
