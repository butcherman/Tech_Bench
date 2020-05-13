@extends('layouts.guest')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>Error: 403</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center text-danger">
            <h4 class="text-center">You are not authorized to view this page</h4>
            <img src="/img/err_img/no.png" alt="sorry" />
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
