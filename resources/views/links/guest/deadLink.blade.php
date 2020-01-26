@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>{{env('APP_NAME')}}</h1></div>
        </div>
    </div>
    <div class="jumbotron">
        <div class="row justify-content-center">
            <div class="col-10">
                <img src="/img/err_img/susp_error.png" alt="Error Image" class="pull-left" />
                <h3>Looking for something?</h3>
                <p>In order to use this function of the {{config('app.name', 'Tech Bench')}}, a valid link ID must be sent to you by one of its registered members.</p>
            </div>
        </div>
    </div>
</div>
@endsection
