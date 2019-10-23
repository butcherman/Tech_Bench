@extends('layouts.app')

@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">My Dashboard</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <h4>Welcome {{Auth::user()->first_name.' '.Auth::user()->last_name}}</h4>
    </div>
</div>
@if(session()->has('status'))
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="alert alert-primary text-center"><h4>{{session()->get('status')}}</h4></div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-12 grid-margin stretch-card">
       <div class="card">
           <div class="card">
               <div class="card-body">
                   This is where the main info goes.
               </div>
           </div>
       </div>
        
    </div>
</div>
@endsection
