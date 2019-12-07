@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>Equipment Categories</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <equipment-categories :categories="{{json_encode($cats)}}"></equipment-categories>
            </div>
        </div>
    </div>
</div>
@endsection
