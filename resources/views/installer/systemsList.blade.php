@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>Select An Equipment Type to Modify</h4>
    </div>
</div>
@if(session()->has('success'))
<div class="row justify-content-center">
    <div class="col-md-8">
        <b-alert variant="success" class="text-center" show dismissible><h3>{{session()->get('success')}}</h3></b-alert>
    </div>
</div>
@endif
<equipment-list :equip_list="{{json_encode($systems)}}"></equipment-list>
@endsection
