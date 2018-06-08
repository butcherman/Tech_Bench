@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Link Details</h1></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 d-flex">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Details:</h5></div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-3 text-right">Link Name:</dt>
                        <dd class="col-9 text-left">{{$data->link_name}}</dd>
                        <dt class="col-3 text-right">Expire Date:</dt>
                        <dd class="col-9 text-left">{{date('M j, Y', strtotime($data->expire))}}</dd>
                        <dt class="col-3 text-right">Allow Uploads</dt>
                        <dd class="col-9 text-left">{{$data->allow_upload}}</dd>
                        <dt class="col-3 text-right">Link:</dt>
                        <dd class="col-9 text-left"><a href="{{route('userLink.details', ['link' => $data->link_hash])}}" id="link-url" target="_blank">{{route('userLink.details', ['link' => $data->link_hash])}}</a></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="card w-100">
                <div class="card-header"><h5 class="card-title">Actions:</h5></div>
                <div class="card-body">
                    <a href="#edit-modal" id="edit-link-details" class="btn btn-block btn-info" data-toggle="modal">Edit Link Details</a>
<!--                    <button class="btn btn-block btn-info copy-btn" data-clipboard-text="{{route('userLink.details', ['link' => $data->link_hash])}}">Copy Link URL To Clipboard</button>-->
<!--                    <a href="" class="btn btn-block btn-info">Email Link</a>-->
                    <a href="#edit-modal" id="delete-link-btn" class="btn btn-block btn-info" data-toggle="modal">Delete Link</a>
                </div>
            </div>   
        </div>
    </div>
    <div class="row pad-top">
        <div class="col-md-6 d-flex">
            <div class="card w-100">
                <div class="card-header"><h5 class="card-title">Files Available to Download:</h5></div>
                <div class="card-body" id="files-to-download">
                    <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex">
            <div class="card w-100">
                <div class="card-header"><h5 class="card-title">Files Uploaded:</h5></div>
                <div class="card-body" id="files-uploaded">
                    <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...
                </div>
            </div>
        </div>
    </div>
</div>
@include('_inc.modal')
@endsection

@section('script')
    @include('links.script.details')
@endsection
