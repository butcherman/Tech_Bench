@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Edit System</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Select System to Edit</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    @foreach($systems as $sys)
        <div class="col-md-4">
            <h4 class="pl-5">{{$sys->name}}</h4>
            <ul class="admin-list">
                @foreach($sys->SystemTypes as $type)
                    <li><a href="{{route('installer.systems.edit', $type->sys_id)}}">{{$type->name}}</a></li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
@endsection
