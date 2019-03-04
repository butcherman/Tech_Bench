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
            <a href="{{route('links.details.create')}}" class="btn btn-block btn-info">Create File Link</a>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-10">
            <div class="table-responsive" id="file-links-table">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" class="check-all-links" /></th>
                            <th>Link Name</th>
                            <th># of Files</th>
                            <th>Expire Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-4">
            <button class="btn btn-info btn-block" id="delete-checked">Delete Checked Links</button>
        </div>
    </div>
</div>
@include('_inc.modal')
@endsection

@section('script')
@include('_js.links.index')
@endsection
