@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pad-top">
        <div class="col-4">
            <h3 class="text-center">Customer Successfully Added</h3>
            <p class="text-center">
                <a href="{{route('customer.details', ['id' => $cust_id, 'name' => $cust_name])}}">Click</a> to go to profile</p><p class="text-center">Or, <a href="{{route('customer.id.create')}}">Click</a> to add another
            </p>
        </div>
    </div>
</div>
@endsection
