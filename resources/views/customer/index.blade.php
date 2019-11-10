@extends('layouts.app')

@section('content.old')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customers</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <customer-list

                get_cust_route="{{route('customer.search')}}"
                new_cust_route="{{route('customer.id.create')}}"

            ></customer-list>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>Customers</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-12" class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <customer-list></customer-list>
            </div>
        </div>
    </div>
</div>
@endsection
