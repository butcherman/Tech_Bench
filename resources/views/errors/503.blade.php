@extends('layouts.guest')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>System Down For Maintenance</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center">
            <img src="/img/err_img/sry_error.png" alt="sorry" class="float-left" />
            <p>
                Cool things are happening behind the scenes.
            </p>
            <p>
                Check back soon.
            </p>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
