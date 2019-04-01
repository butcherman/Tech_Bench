@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Categories</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Select A Category to Edit</h1></div>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-4">
            <ul class="list-group">
                @foreach($cats as $cat)
                    <li class="list-group-item">
                        <a href="{{route('installer.categories.edit', ['id' => $cat->cat_id])}}" class="btn btn-block btn-primary">{{$cat->name}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
