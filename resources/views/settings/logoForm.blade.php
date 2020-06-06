@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>{{config('app.name')}} Logo</h4>
    </div>
</div>
<tech-bench-logo logo="{{config('app.logo')}}"></tech-bench-logo>
@endsection
