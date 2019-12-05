@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col grid-margins">
        <h4>User Administration</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <user-deleted
                    :user_list="{{json_encode($userList)}}"
                    action_route="{{$route}}"
                ></user-deleted>
            </div>
        </div>
    </div>
</div>
@endsection
