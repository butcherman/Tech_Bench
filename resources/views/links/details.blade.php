@extends('layouts.app')

@section('old_content')
{{--


<div class="row pad-top pad-bottom">
    <div class="col-md-12 d-flex">
        <div class="card w-100">
            <div class="card-header"><h5 class="card-title">Files:</h5></div>
            <div class="card-body" id="files-to-download">
                <link-files
                    link_id="{{$link_id}}"
                    files_route="{{route('links.files.index')}}"
                    has_customer="{{$has_cust}}"
                    download_route="{{route('download', [':fileID', ':filename'])}}"
                    customer_file_types="{{json_encode($file_types)}}"
                    download_all_route="{{route('downloadAll')}}"
                >
                </link-files>
            </div>
        </div>
    </div>
</div>

--}}
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>File Links</h4>
    </div>
</div>
<link-details
    link_id="{{$link_id}}"
></link-details>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">Link Files</div>
            <div class="card-body">
                <link-files
                    link_id="{{$link_id}}"
                    cust_id="{{$cust_id}}"
                    file_types="{{json_encode($file_types)}}"
                ></link-files>
            </div>
        </div>
    </div>
</div>
@endsection
