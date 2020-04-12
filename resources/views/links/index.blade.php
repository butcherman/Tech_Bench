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
            <div class="card-header text-center"><b-button href="{{route('links.new')}}" variant="info" pill>Create New File Link</a></div>
            <div class="card-body">
                <list-file-links></list-file-links>
            </div>
        </div>
    </div>
</div>
@endsection
