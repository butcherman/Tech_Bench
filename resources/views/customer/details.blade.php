@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customers</a></li>
    <li class="breadcrumb-item active">{{$details->name}}</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12"> 
        <customer-details
            cust_id="{{$details->cust_id}}"
            is_fav="{{$isFav}}"
            fav_route="{{route('customer.toggleFav', [':action', $details->cust_id])}}"
            show_route="{{route('customer.id.show', $details->cust_id)}}"
            edit_route="{{route('customer.id.update', $details->cust_id)}}"
        ></customer-details>
    </div>
</div>
<div class="row pad-bottom">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header text-center"><h5>Systems:</h5></div>
            <div class="card-body" id="system-information">
                <customer-systems
                    cust_id="{{$details->cust_id}}"
                    get_sys_route="{{route('customer.systems.show', $details->cust_id)}}"
                    sys_data_route="{{route('customer.getDataFields', ':id')}}"
                    new_sys_route="{{route('customer.systems.store')}}"
                    edit_sys_route="{{route('customer.systems.update', ':id')}}"
                    sys_list="{{json_encode($sysList)}}"
                ></customer-systems>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header text-center"><h5>Contacts:</h5></div>
            <div class="card-body" id="contact-information">
                <customer-contacts
                    cust_id="{{$details->cust_id}}"
                    phone_types="{{json_encode($phoneTypes)}}"
                    get_contacts_route="{{route('customer.contacts.show', $details->cust_id)}}"
                    new_contact_route="{{route('customer.contacts.store')}}"
                    edit_contact_route="{{route('customer.contacts.update', ':id')}}"
                ></customer-contacts>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center pad-bottom">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Notes:</h5></div>
            <div class="card-body" id="customer-notes">
                <customer-notes
                    cust_id="{{$details->cust_id}}"
                    add_note_route="{{route('customer.notes.store')}}"
                    download_note_route="{{route('customer.download-note', ':id')}}"
                ></customer-notes>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center pad-bottom">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Files:</h5></div>
            <div class="card-body" id="customer-files">
                <customer-files
                    cust_id="{{$details->cust_id}}"
                    file_types="{{$fileTypes}}"
                    download_route="{{route('download', [':id', ':name'])}}"
                    file_route="{{route('customer.files.store')}}"
                ></customer-files>
            </div>
        </div>
    </div>
</div>
@endsection