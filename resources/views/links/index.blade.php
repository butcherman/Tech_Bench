@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>File Links</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <a href="{{route('links.new')}}" class="btn btn-block btn-info">Create New File Link</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <list-file-links><img src="{{asset('img/loading.svg')}}" alt="Loading..." class="d-block mx-auto"></list-file-links>
            </div>
        </div>
    </div>
</div>
@endsection
