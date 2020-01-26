@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <h4>{{config('app.name')}} Configuration</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session()->has('success'))
            <div class="alert alert-success"><h3 class="text-center">{!!session('success')!!}</h3></div>
        @endif
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <tb-configuration
                    :settings="{{json_encode($settings)}}"
                    :timezones="{{json_encode($timzone_list)}}"
                ></tb-configuration>
            </div>
        </div>
    </div>
</div>
@endsection
