@extends('layouts.app')

@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">My Dashboard</a></li>
    <li class="breadcrumb-item active">File Links</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>File Links</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <a href="{{route('links.data.create')}}" class="btn btn-block btn-info">Create File Link</a>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-10">
            <div class="table-responsive" id="file-links-table">
                <list-file-links 
                    get_links_route="{{route('links.user', Auth::user()->user_id)}}" 
                    del_link_route="{{route('links.data.destroy', ':linkID')}}"             
                    em_link_route="mailto:?subject=A File Link Has Been Created For You&body=View the link details here: {{route('file-links.show', ':hash')}}"
                >
                </list-file-links>
            </div>
        </div>
    </div>
</div>
@endsection
