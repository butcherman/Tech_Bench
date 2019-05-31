@extends('layouts.app')

@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">My Dashboard</a></li>
    <li class="breadcrumb-item active">About</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center pad-top">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>About Tech Bench</h1></div>
    </div>
</div>
<div class="row justify-content-center pad-top">
    <div class="col-md-8">
        <p class="text-center">Tech Bench &copy;2016-2019</p>
        <p class="text-center">Version - @version('version')</p>
    </div>
</div>
<div class="row justify-content-center pad-top">
    <div class="col-md-8">
        <p class="text-center">
            Looking for help?  <a href="https://tech-bench.readthedocs.io/en/{{$branch}}/user/index.html" target="_blank">Click here for the Tech Bench User Guide.</a>
        </p>
    </div>
</div>
@if(Auth::user()->hasAnyRole(['admin', 'installer']))
    <div class="row justify-content-center pad-top">
        <div class="col-md-8">
            <p class="text-center">
                Check out the Tech Bench project online - visit us at <a href="https://github.com/butcherman/Tech_Bench">GitHub.com</a>
            </p>
            <p class="text-center">
                Installer and Administration guides can be found at <a href="https://tech-bench.readthedocs.io/en/{{$branch}}">readthedocs.org</a>.
            </p>
            <p class="text-center">
                To report an issue, contact us through <a href="https://github.com/butcherman/Tech_Bench/issues">this link</a>.
            </p>
        </div>
    </div>
@endif
@endsection
