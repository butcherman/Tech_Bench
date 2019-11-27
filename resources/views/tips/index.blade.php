@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>Tech Tips</h4>
    </div>
</div>
<search-tips :tip_types="{{json_encode($tipTypes)}}" :sys_types="{{json_encode($sysTypes)}}"></search-tips>
@endsection
