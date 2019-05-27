@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Tech Tips</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <form>
            <input type="text" class="form-control" placeholder="search" />
        </form>
    </div>
    <div class="col-md-2">
        <a href="{{route('tip.id.create')}}" title="New Tech Tip" class="btn btn-primary btn-block" data-toggle="tooltip"><i class="fa fa-plus" aria-hidden="true"></i> Create New Tech Tip</a>
    </div>
</div>
   
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Tech Tips</h1></div>
    </div>
</div>

@endsection
