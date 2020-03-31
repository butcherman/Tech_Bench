@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>About Tech Bench</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <img src="{{asset('/img/TechBenchLogo.png')}}" class="mx-auto d-block img-fluid" alt="Tech Bench"/>
                <p class="text-center">
                    Tech Bench &copy; {{config('app.copyright')}}
                    <span class="d-inline-block">- All Rights Reserved</span>
                </p>
                <p class="text-center">Version - @version('full')</p>
                <p class="text-center">Build Date - @version('timestamp-simple')</p>
                <p class="text-center">
                    Looking for guidance?
                    <span class="d-inline-block">
                        <a href="https://tech-bench.readthedocs.io/en/{{$branch}}/user/index.html" target="_blank">Click here for the Tech Bench User Guide.</a>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@can('is_installer')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body"><h4 class="text-center">Looking For More?</h4>
                <p class="text-center">
                    Check out the Tech Bench project online -
                    <span class="d-inline-block">
                        visit us at
                        <a href="https://github.com/butcherman/Tech_Bench">GitHub.com</a>
                    </span>
                </p>
                <p class="text-center">
                    Installer and Administration guides can be
                    <span class="d-inline-block">
                        found at <a href="https://tech-bench.readthedocs.io/en/{{$branch}}">readthedocs.org</a>.
                    </span>
                </p>
                <p class="text-center">
                    To report an issue, contact us through <a href="https://github.com/butcherman/Tech_Bench/issues">this link</a>.
                </p>
            </div>
        </div>

    </div>
</div>
@endcan
@endsection
