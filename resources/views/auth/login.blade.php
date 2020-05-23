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
                @error('username')
                    <h6 class="text-danger text-center">{{$message}}</h6>
                @enderror
                <b-form method="post" action="/login">
                        @csrf
                        <b-form-group
                            label="Username:"
                            label-for="username"
                        >
                            <b-form-input
                                id="username"
                                name="username"
                                type="text"
                                required
                                placeholder="Username"></b-form-input>
                        </b-form-group>
                        <b-form-group
                            label="Password:"
                            label-for="password"
                        >
                            <b-form-input
                                id="password"
                                name="password"
                                type="password"
                                required
                                placeholder="Password"></b-form-input>
                        </b-form-group>
                        <b-checkbox class="no-validate" name="remember">Remember Me</b-checkbox>
                    <form-submit button_text="Login" ></form-submit>
                    <div class="form-group row justify-content-center mb-0">
                        <div class="col-md-8 text-center">
                            <a class="btn btn-link text-muted" href="{{ route('password.request') }}">Forgot Your Password?</a>
                        </div>
                    </div>
                </b-form>
            </div>
        </div>
    </div>
</div>
@endsection
