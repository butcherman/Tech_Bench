@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Email Settings</h4>
    </div>
</div>
<email-settings :settings="{{json_encode($settings)}}"></email-settings>
@endsection
