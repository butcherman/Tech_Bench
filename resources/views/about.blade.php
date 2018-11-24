@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">My Dashboard</a></li>
    <li class="breadcrumb-item active">About Tech Bench</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h1>About Tech Bench</h1></div>
                <div class="card-body">
                    <dl class="row justify-content-center">
                        <dt class="col-sm-6 text-right">Version:</dt>
                        <dd class="col-sm-6">@version('compact')</dd>
<!--
                        <dt class="col-sm-6 text-right">Release Date:</dt>
                        <dd class="col-sm-6">{{config('app.release')}}</dd>
-->
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
