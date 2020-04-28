@extends('layouts.guest')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>Error: 404</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center text-danger">
            <h4 class="text-center">I cannot seem to find the page you are looking for</h4>
            <img src="/img/err_img/search.png" alt="sorry" />
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
