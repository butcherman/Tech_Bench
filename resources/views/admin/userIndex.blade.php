@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col grid-margins">
        <h4>User Administration</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <user-list
                    :user_list="{{$userList}}"
                ></user-list>
            </div>
        </div>
    </div>
</div>
@endsection
