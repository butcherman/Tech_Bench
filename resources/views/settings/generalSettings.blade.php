@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>{{config('app.name')}} General Settings</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <tech-bench-settings :tz_list='@json($tz_list)' :settings='@json($settings)'></tech-bench-settings>
            </div>
        </div>
    </div>
</div>
@endsection
