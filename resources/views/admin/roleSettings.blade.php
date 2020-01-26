@extends('layouts.app')

@section('content')
<div class="row grid-margin">
    <div class="col-12">
        <h4>User Permissions and Roles</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <user-roles :roles="{{json_encode($roles)}}" :permissions="{{json_encode($perms)}}"></user-roles>
            </div>
        </div>
    </div>
</div>
@endsection
