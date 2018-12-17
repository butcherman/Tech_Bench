@extends('layouts.guest')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>Error: 404</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center">
            <img src="/img/err_img/no.png" alt="sorry" class="float-left" />
            <p>
                You do not have permission to view this page.
            </p>
            <button class="btn btn-default" id="goBack">Click To Go Back</button>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
    
<script>
    $('#goBack').click(function()
    {
        parent.history.back();
        return false;
    });
</script>
@endsection
