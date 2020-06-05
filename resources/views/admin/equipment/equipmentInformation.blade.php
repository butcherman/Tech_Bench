@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Equipment Information</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <equipment-information :data='@json($fields)'></equipment-information>
            </div>
        </div>
    </div>
</div>
@endsection
