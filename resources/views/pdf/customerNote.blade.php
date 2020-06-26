@extends('pdf.template')

@section('content')
<div class="row justify-content-center">
    <div class="col-10">
        <h3>Customer Note for - {{$cust_name}}</h3>
        <h4>Subject - {{$subject}}</h4>
    </div>
</div>
<div class="row justify-content-center mt-5">
    <div class="col-9 border-top">
        {!! $description !!}
    </div>
</div>
@endsection
