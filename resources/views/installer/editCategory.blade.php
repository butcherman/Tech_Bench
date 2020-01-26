@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('installer.categories.index')}}">Categories</a></li>
    <li class="breadcrumb-item active">{{$details->name}}</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Edit Category</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session()->has('success'))
                <div class="alert alert-success"><h3 class="text-center">{!!session('success')!!}</h3></div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            {!!Form::model($details, ['route' => ['installer.categories.update', $details->cat_id]])!!}
                @method('PUT')
                {{Form::bsText('name', 'Category Name', null, ['required'])}}
                {{Form::bsSubmit('Update Category')}}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
