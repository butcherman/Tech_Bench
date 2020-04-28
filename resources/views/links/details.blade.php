@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>File Link Details</h4>
    </div>
</div>
<link-details
    link_id="{{$link_id}}"
></link-details>
<link-instructions
    link_id="{{$link_id}}"
></link-instructions>
<link-files
    link_id="{{$link_id}}"
    cust_id="{{$cust_id}}"
    :file_types="{{json_encode($file_types)}}"
></link-files>
@endsection
