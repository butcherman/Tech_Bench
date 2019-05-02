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

@endsection
