@extends('layouts.app')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>Error:</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="jumbotron text-center">
            <img src="/img/err_img/sry_error.png" alt="Error Image" class="float-left" />
            <p>
                We are sorry but the customer you are looking for does not exist or cannot be found.
            </p>
            <p>
                A log has been generated and our minions are busy at work to determine what went wrong.
            </p>
            <button class="btn btn-default" id="goBack">Click To Go Back</button>
            <div class="clearfix"></div>
        </div>
    </div>
</div>   
@endsection

@section('script')
<script>
    $('#goBack').click(function()
    {
        parent.history.back();
        return false;
    });
</script>
@endsection
