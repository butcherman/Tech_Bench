@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Error</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body d-flex flex-row">
                <img src="/img/err_img/sry_error.png" alt="Error Image" />
                <div class="my-auto ml-4">
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
</div>
@endsection
