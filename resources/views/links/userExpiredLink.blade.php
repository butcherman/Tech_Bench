@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Error</h1></div>
        </div>
    </div>
    <div class="jumbotron">
        <div class="row justify-content-center">
            <div class="col-10">
                <img src="/img/err_img/sry_error.png" alt="Expired Link" class="pull-left img-responsive" />
                <h3>Your Download Link Has Expired</h3>
                <p>
                    If you think you have reached this message in error, contact the person that sent you this link to request that the link be re-activated.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
