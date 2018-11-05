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
                <img src="/img/err_img/sry_error.png" alt="Error Image" class="pull-left" />
                <p>
                    We are sorry but the link you are looking for does not exist or cannot be found.
                </p>
                <p>
                    A log has been generated and our minions are busy at work to determine what went wrong.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
