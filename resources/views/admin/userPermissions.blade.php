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
                <user-permissions :roles_list='@json($roles)' :perms_list='@json($perms)'></user-permissions>
            </div>
        </div>
    </div>
</div>
@endsection
