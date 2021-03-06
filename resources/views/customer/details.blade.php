@extends('layouts.app')

@section('content')
<customer-master
    :cust_details="{{$details}}"
    :is_fav="{{$isFav}}"
    @can('hasAccess', 'deactivate_customer')
    :can_del="true"
    @endcan
></customer-master>
@endsection
