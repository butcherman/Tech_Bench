@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Customer File Types</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                When uploading a file for a customer, the following options are available as file types:
            </div>
            <div class="card-body">
                <customer-file-types></customer-file-types>
            </div>
        </div>
    </div>
</div>
@endsection
