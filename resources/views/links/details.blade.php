@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">My Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/links">File Links</a></li>
    <li class="breadcrumb-item active">Link Details</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Link Details</h1></div>
            </div>
        </div>
    </div>
</div>
<link-details 
    link_id="{{$link_id}}"
    index_route="{{route('links.index')}}"
    details_route="{{route('links.data.show', $link_id)}}"
    update_route="{{route('links.data.update', $link_id)}}"        
    guest_route="{{route('file-links.show', ':hash')}}"         
    search_route="{{route('customer.searchID', ':id')}}"
    update_cust_route="{{route('links.updateCustomer', ':id')}}"
    em_link_route="mailto:?subject=A File Link Has Been Created For You&body=View the link details here: {{route('file-links.show', ':hash')}}"
    del_link_route="{{route('links.data.destroy', ':linkID')}}"
    >
</link-details>
<link-instructions
    link_id="{{$link_id}}"
    instructions_route="{{route('links.instructions', $link_id)}}"     
    >
</link-instructions>
<div class="row pad-top">
    <div class="col-md-6 d-flex">
        <div class="card w-100">
            <div class="card-header"><h5 class="card-title">Files Available to Download:</h5></div>
            <div class="card-body" id="files-to-download">
                <link-files
                    file_direction="down"
                    files_route="{{route('links.files', [$link_id, 'down'])}}"
                    has_customer="{{$has_cust}}"
                    get_file_types_route="{{route('customer.getFileTypes')}}"
                    del_file_route="{{route('links.delFile', ':file')}}"
                    dl_all_route="{{route('downloadAll')}}"
                >
                </link-files>
            </div>
        </div>
    </div>
    <div class="col-md-6 d-flex">
        <div class="card w-100">
            <div class="card-header"><h5 class="card-title">Files Uploaded:</h5></div>
            <div class="card-body" id="files-uploaded">
                <link-files
                    file_direction="up"
                    files_route="{{route('links.files', [$link_id, 'up'])}}"
                    has_customer="{{$has_cust}}"
                    get_file_types_route="{{route('customer.getFileTypes')}}"
                    del_file_route="{{route('links.delFile', ':file')}}"
                    dl_all_route="{{route('downloadAll')}}"
                >
                </link-files>
            </div>
        </div>
    </div>
</div>
@endsection
