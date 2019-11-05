@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-8 col-xl-6">
        <div class="row" id="header-title">
            <div class="col-12">
                <h1>{{config('app.name', 'Tech Bench')}}</h1>
            </div>
        </div>
        <div class="row row-eq-height justify-content-center align-items-center login-form-sub-container">
            <div class="col-md-6">
                <img src="{{config('app.logo')}}" alt="Company Logo" id="header-logo" />
            </div>
            <div class="col-md-6">
                <h3>Looking for something?</h3>
                <p>In order to use this function of the {{config('app.name', 'Tech Bench')}}, a valid link ID must be sent to you by one of its registered members.</p>
            </div>
        </div>
    </div>
</div>
@endsection
