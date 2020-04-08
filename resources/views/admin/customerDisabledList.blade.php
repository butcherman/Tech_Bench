@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>View Disabed Customers</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <customers-disabled :cust_list="{{json_encode($custList)}}"></customers-disabled>
            </div>
        </div>
    </div>
</div>
@endsection
