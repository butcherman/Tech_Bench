@extends('layouts.app')

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
                <customer-list
                    :system_types="{{json_encode($sysTypes)}}"
                @can('hasAccess', 'Add Customer')
                    allow_create="true"
                @endcan
                ></customer-list>
            </div>
        </div>
    </div>
</div>
@endsection
