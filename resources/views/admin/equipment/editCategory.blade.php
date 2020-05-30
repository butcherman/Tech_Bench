@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>@if($data) Edit @else New @endif Equipment Category</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-5 col-sm-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <equipment-category-form :cat_info='@json($data)'></equipment-category-form>
            </div>
        </div>
    </div>
</div>
@endsection
