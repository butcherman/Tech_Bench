@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customers</a></li>
    <li class="breadcrumb-item active">New Customer</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>New Customers</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <h5 class="text-center">Error: {{ $error }}</h5>
                    @endforeach
                </div>
            @endif
            {!! Form::open(['route' => 'customer.id.store']) !!}
                <div class="row">
                    <div class="col-6">
                        {{ Form::bsText('cust_id', 'Customer Site ID', '', ['required', 'autofocus']) }}
                    </div>
                </div>
                {{ Form::bsText('name', 'Customer Name', '', ['required']) }}
                {{ Form::bsText('dba_name', 'DBA Name/AKA') }}
                {{ Form::bsText('address', 'Address', '', ['required']) }}
                {{ Form::bsText('city', 'City', '', ['required']) }}
                <div class="row">
                    <div class="col-6">
                        {{ Form::allStates('CA') }}
                    </div>
                    <div class="col-6">
                        {{ Form::bsNumber('zip', 'Zip Code', '', ['maxlength' => 5, 'minlength' => 5, 'required']) }}
                    </div>
                </div>
                {{ Form::bsSubmit('Add Customer') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
