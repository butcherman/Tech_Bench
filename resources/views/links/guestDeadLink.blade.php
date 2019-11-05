@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-10 col-xl-8">
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
                <h3 class="text-danger">Well, this is embarresing...</h3>
                <p>
                    It seems you were given a valid link, but nothing has been added to it yet.
                </p>
                <p>
                    Please try again later.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

