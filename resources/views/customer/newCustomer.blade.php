@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customers</a></li>
    <li class="breadcrumb-item active">New Customer</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>New Customer</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <new-customer-form><img src="{{asset('img/loading.svg')}}" alt="Loading..." class="d-block mx-auto"></new-customer-form>
            </div>
        </div>
    </div>
</div>
@endsection
