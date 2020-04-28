@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center login-form-container">
    <div class="col-lg-10 col-xl-8">
        <div class="row" id="header-title">
            <div class="col-12">
                <h1>{{config('app.name', 'Tech Bench')}}</h1>
            </div>
        </div>
        @if($instructions)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Details:</h4>
                    </div>
                    <div class="card-body" id="guest-link-instructions">
                        {!! $instructions !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($hasFiles)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4>You Have Files Available to Download</h4></div>
                    <div class="card-body">
                        <guest-download link_id="{{$hash}}"></guest-download>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($allowUp)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4>Upload File</h4></div>
                    <div class="card-body">
                        <guest-upload link_id="{{$hash}}"></guest-upload>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


