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
                <img src="{{asset('img/err_img/sry_error.png')}}" alt="Error" id="header-logo" />
            </div>
            <div class="col-md-6">
                <h3 class="text-danger">Error:</h3>
                <p>
                    We are sorry but the link you are looking for does not exist or cannot be found.
                </p>
                <p>
                    A log has been generated and our minions are busy at work to determine what went wrong.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
