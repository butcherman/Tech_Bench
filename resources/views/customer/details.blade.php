@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <customer-details
            :cust_details="{{$details}}"
            :is_fav="{{$isFav}}"
        @can('hasAccess', 'deactivate_customer')
            :can_del="true"
        @endcan
        >
        </customer-details>
    </div>
</div>
@endsection
