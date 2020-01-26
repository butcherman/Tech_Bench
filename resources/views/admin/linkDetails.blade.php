@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>File Links for {{$user->full_name}}</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <list-file-links user_id="{{$user->user_id}}"><img src="{{asset('img/loading.svg')}}" alt="Loading..." class="d-block mx-auto"></list-file-links>
            </div>
        </div>
    </div>
</div>
@endsection
