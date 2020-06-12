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
                <customer-search-full
                    :equipment_types='@json($equipList)'
                @can('hasAccess', 'Add Customer')
                    :allow_create="true"
                @endcan
                ></customer-search-full>
            </div>
        </div>
    </div>
</div>
@endsection
