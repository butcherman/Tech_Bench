@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Equipment</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Select A Category or Equipment Type to Modify</div>
                {{-- {{dd($equip->toArray())}} --}}
                <div class="row">
                    @foreach($equip as $e)
                    <div class="col-md-3">
                        <a href="{{route('admin.equipment.categories.edit', $e->cat_id)}}" class="text-reset"><h6>{{$e->name}}</h6></a>
                    </div>
                    @endforeach
                </div>
                <div class="row mt-5">
                    <div class="col text-center">
                        <b-button href="{{route('admin.equipment.categories.create')}}" pill variant="info">Create New Category</b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
