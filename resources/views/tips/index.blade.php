@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>Tech Tips</h4>
    </div>
</div>
<search-tech-tips
    :tip_types='@json($tipTypes)'
    :equipment='@json($equipment)'
    @can('hasAccess', 'Create Tech Tip')
    :can_create="true"
    @endcan
></search-tech-tips>
@endsection
