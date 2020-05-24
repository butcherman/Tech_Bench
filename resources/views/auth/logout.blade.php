@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-8 col-xl-6">
        <div class="row" id="header-title">
            <div class="col-12"><h1>{{config('app.name', 'Tech Bench')}}</h1></div>
        </div>
        <div class="row row-eq-height justify-content-center align-items-center login-form-sub-container">
            <div class="col-md-6">
                <img src="{{config('app.logo')}}" alt="Company Logo" id="header-logo" />
            </div>
            <div class="col-md-6">
                <noscript>
                    <h4 class="text-center">Javascript is Disabled</h4>
                    <p class="text-center">
                        {{config('app.name')}} requires Javascript to work properly.
                    </p>
                    <p class="text-center">
                        Please enable Javascript and reload page
                    </p>
                </noscript>
                <h4 class="text-center">Successfully Logged out</h4>
                <div class="text-center">
                    <b-button href="/login" class="ml-auto" variant="info">Click Here to Log Back In</b-button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
