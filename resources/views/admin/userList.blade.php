@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col grid-margins">
        @if($active)
        <h4>User Administration</h4>
        @else
        <h4>Deactivated Users</h4>
        @endif
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if($active)
                <active-user-list
                    :user_list='@json($userList)'
                ></active-user-list>
                @else
                <inactive-user-list
                    :user_list='@json($userList)'
                ></inactive-user-list>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
