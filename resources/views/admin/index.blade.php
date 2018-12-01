@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>System Administration</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>General</strong></div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{route('admin.users.index')}}" class="category-button btn btn-link btn-block">Users</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{route('admin.links')}}" class="category-button btn btn-link btn-block">File Links</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
