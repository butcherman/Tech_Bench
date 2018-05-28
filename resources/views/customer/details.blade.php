@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12"> 
            <h2>
                <i class="fa fa-bookmark {{is_null($isFav) ? 'bookmark-unchecked' : 'bookmark-checked'}}" aria-hidden="true" title="Bookmark Customer" data-tooltip="tooltip"></i> 
                {{$details->name}}</h2>
            <h5>{{$details->dba_name}}</h5>
            <address>
                <a href="https://maps.google.com/?q={{urlencode($details->address.', '.$details->city.', '.$details->state)}}" target="_blank" id="addr-span">
                    {{$details->address}}<br />
                    {{$details->city}}, {{$details->state}} &nbsp;{{$details->zip}}
                </a>
                <a href="#edit-modal" title="Edit Customer Information" data-tooltip="tooltip" data-toggle="modal" class="text-muted" id="edit-customer"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            </address>
        </div>
    </div>
    <div class="row justify-content-center pad-bottom">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center"><h5>Systems:</h5></div>
                <div class="card-body" id="system-information">
                    <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header text-center"><h5>Contacts:</h5></div>
                <div class="card-body" id="contact-information">
                    <div class="table-responsive">
                        <table class="table" id="contact-information">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <a href="#edit-modal" id="add-contact-btn" class="btn btn-info" data-toggle="modal">Add Contact</a>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pad-bottom">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>Notes:</h5></div>
                <div class="card-body" id="customer-notes">
                    <p class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pad-bottom">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>Files:</h5></div>
                <div class="card-body" id="customer-files">
                    <p class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</p>
                </div>
            </div>
        </div>
    </div>
<!--        Linked Sites to be Added Later
    <div class="row justify-content-center pad-bottom">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>Linked Sites:</h5></div>
                <div class="card-body" id="customer-linked-sites">
                    <p class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</p>
                </div>
            </div>
        </div>
    </div>
-->
</div>
@include('_inc.modal')
@endsection

@section('script')
@include('customer.script.details')
@endsection
