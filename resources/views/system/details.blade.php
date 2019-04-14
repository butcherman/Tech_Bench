@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.index')}}">Systems</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.select', ['cat' => $category])}}">{{$category}}</a></li>
    <li class="breadcrumb-item active">{{$sysName}}</li>
</ol>
@endsection

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>{{$sysName}}</h1>
</div>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <system-files
            file_route="{{route('system.system-files.show', $sysID)}}"
            new_file_route="{{route('system.system-files.store')}}"
            edit_file_route="{{route('system.system-files.update', ':id')}}"
            replace_file_route="{{route('system.replaceFile')}}"
            delete_route="{{route('system.system-files.destroy', ':id')}}"
            sys_id="{{$sysID}}"
        ></system-files>
    </div>
</div>
@endsection
