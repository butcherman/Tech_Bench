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
@endsection
