@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <h4>User File Links</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="table-responsive">
            <admin-file-links 
                :user_links="{{json_encode($links)}}"
            ></admin-file-links>
        </div>
    </div>
</div>
@endsection
