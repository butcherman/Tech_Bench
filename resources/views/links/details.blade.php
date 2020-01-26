@extends('layouts.app')

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
                    :file_types="{{json_encode($file_types)}}"
                ></link-files>
            </div>
        </div>
    </div>
</div>
@endsection
