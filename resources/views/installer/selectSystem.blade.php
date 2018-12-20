@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Edit System</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Select System to Edit</h1></div>
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
    @foreach($categories as $cat)
        <div class="row justify-content-center pad-top">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>{{$cat->name}}</strong>
                    </div>
                    <div class="card-body">
                        @foreach($cat->SystemTypes->chunk(3) as $sys)
                            <div class="row pad-top">
                                @foreach($sys as $s)
                                    <div class="col-md-4">
                                        <a href="{{route('installer.systems.edit', ['id' => $s->name])}}" class="btn btn-block btn-info">{{$s->name}}</a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <a href="{{route('installer.newSys', ['id' => $cat->name])}}" class="btn btn-block btn-secondary">Create New System</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
