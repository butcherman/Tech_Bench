@extends('layouts.app')

@section('content');
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Create New User</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <new-user-form :role_list='@json($roles)'></new-user-form>
            </div>
        </div>
    </div>
</div>
@endsection
