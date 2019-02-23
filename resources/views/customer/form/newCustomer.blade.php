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
                        <span class="invalid-feedback" id="duplicate-customer"></span>
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

@section('script')
<script>
    $('#cust_id').on('blur', function()
    {
        if(!$(this).val() == '')
        {
            var check = $(this).val();
            var url = '{{route('customer.checkId', ['id' => ':id'])}}';
            url = url.replace(':id', check);
            $.get(url, function(res)
            {
                if(res != 'false')
                {
                    var url2 = '{{route('customer.details', ['id' => ':id', 'name' => ':name'])}}';
                    url2 = url2.replace(':id', check).replace(':name', res);
                    $('#duplicate-customer').html('<strong>This customer ID Already exists.  Click <a href="'+url2+'">here</a> to visit their profile.').show();
                }
            });
        }
    });
</script>
@endsection
