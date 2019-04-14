@extends('layouts.app')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>Error:</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center">
            <img src="/img/err_img/sry_error.png" alt="sorry" class="float-left" />
            <p>
                We are sorry but the system you are looking for does not exist or cannot be found.
            </p>
            <p>
                A log has been generated and our minions are busy at work to determine what went wrong.
            </p>
            <go-back></go-back>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
