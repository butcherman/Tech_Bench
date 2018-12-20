@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">New Category</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Add System Category</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session()->has('success'))
                <div class="alert alert-success">{!!session('success')!!}</div>
            @endif
            @if($errors->any())
                @foreach($errors->all() as $err)
                    <div class="alert alert-danger">
                        <h2 class="text-center">{{$err}}</h2>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!!Form::open(['route' => 'installer.system-categories.store'])!!}
                {{Form::bsText('name', 'Enter Category Name', null, ['required'])}}
                {{Form::bsSubmit('Submit Category')}}
            {!!Form::close()!!}
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center"><strong>Instructions</strong></div>
                <div class="card-body">
                    <p>
                        A new System Category will allow for individual systems to be created under it.  The category will display in the navigation menu to the left.
                    </p>
                    <p>
                        The Category name must be a standard string, no special characters are allowed.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
