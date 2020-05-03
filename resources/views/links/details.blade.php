@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>File Link Details</h4>
    </div>
</div>
<link-details
    :init_details="{{json_encode($details)}}"
></link-details>
@endsection
