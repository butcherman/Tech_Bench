@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col grid-margin stretch-card">
        <h4>Add On Modules</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Installed Modules</div>
                    <active-modules></active-modules>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Staged Modules</div>
                <staged-modules></staged-modules>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Upload Module</div>
                <upload-module></upload-module>
            </div>
        </div>
    </div>
</div>
@endsection
