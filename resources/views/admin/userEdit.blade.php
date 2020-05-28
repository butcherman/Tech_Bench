@extends('layouts.app')

@section('content');
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Edit User</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <edit-user-form :role_list='@json($roles)' :user_details='@json($details)'></edit-user-form>
            </div>
        </div>
    </div>
</div>
@endsection
