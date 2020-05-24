@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <h4 class="text-center text-md-left">Welcome {{Auth::user()->first_name.' '.Auth::user()->last_name}}</h4>
    </div>
</div>
@if(session()->has('status'))
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="alert alert-primary text-center"><h4>{{session()->get('status')}}</h4></div>
    </div>
</div>
@endif
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Notifications</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin d-md-flex d-none pr-0">
        <div class="row w-100">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Tech Tips:</p>
                        <h3>zz New Tips</h3>
                        <p class="text-small">(Last 30 Days)</p>
                        <h4>zz Total Tips</h4>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="card-title">File Links:</p>
                        <h3>zz Active File Links</h3>
                        <h4>zz Total File Links</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Customer Favorites</h4>
                <div class="row">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tech Tip Favorites</h4>
                <div class="row">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
