@extends('layouts.guest')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>SERVER OFFLINE FOR MAINTENANCE</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center text-danger">
            <h4 class="text-center">We are hard at work running scheduled maintenance</h4>
            <p>Check back soon!!!</p>
            <img src="/img/err_img/sleep.png" alt="sorry" />
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
