@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">Select User</a></li>
    <li class="breadcrumb-item active">Disable {{$name}}</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Confirm Disable User</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <h3 class="text-center">Are You Sure You Want to Continue?</h3>
        {!! Form::open(['route' => ['admin.user.destroy', $id], 'id' => 'reset-password-form']) !!}
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Confirm Disable {{$name}}"/>
        {!! Form::close() !!}
    </div>
</div>
@endsection
