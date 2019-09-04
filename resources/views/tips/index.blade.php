@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Tech Tips</li>
</ol>
@endsection

@section('content')
<search-tech-tips
    tips_route="{{route('tips.index')}}"
    search_route="{{route('tips.search')}}"
    filter_types="{{json_encode($filterTypes)}}"
    system_types="{{json_encode($systemTypes)}}"
></search-tech-tips>
<div class="clearfix mt-3"></div>
@endsection
