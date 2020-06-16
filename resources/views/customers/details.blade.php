@extends('layouts.app')

@section('content')
<customer-details
    :customer_details='@json($details)'
    :parent_details='@json($parent)'
    :is_fav='@json($isFav)'
    @can('hasAccess', 'Deactivate Customer')
    :can_deactivate="true"
    @endcan
></customer-details>
<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <customer-equipment
            :cust_id='@json($details->cust_id)'
            :linked_site='@json($parent || $details->childCount ? true : false)'
        ></customer-equipment>
    </div>
</div>
@endsection
