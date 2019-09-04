@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tips.index')}}">Tech Tips</a></li>
    <li class="breadcrumb-item active">New Tech Tip</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Create New Tech Tip or System Documentation</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-12">
        <new-tip-form
            tips_route="{{route('tips.index')}}"
            image_route="{{route('tips.processImage')}}"
            system_types="{{json_encode($sysTypes)}}"
            tip_types="{{json_encode($tipTypes)}}"
        ></new-tip-form>
    </div>
</div>
<div class="clearfix pad-bottom">&nbsp;</div>
@endsection
