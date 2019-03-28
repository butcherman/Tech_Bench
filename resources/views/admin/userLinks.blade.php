@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">User File Links</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User File Links Administration</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="table-responsive">
                <admin-file-links 
                    links_route="{{route('admin.countLinks')}}"
                ></admin-file-links>
            </div>
        </div>
    </div>
</div>
@endsection
