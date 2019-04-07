@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('installer.systems.index')}}">Edit System</a></li>
    <li class="breadcrumb-item">{{$name}}</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Edit {{$name}}</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <edit-system-form
            dropdown="{{json_encode($dropDown)}}"
            get_route="{{route('installer.systems.show', $sys_id)}}"
            submit_route="{{route('installer.systems.update', $sys_id)}}"
            finish_route="{{route('admin.index')}}"
        ></edit-system-form>
    </div>
</div>
<div class="row justify-content-center pad-top">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center"><strong>Instructions</strong></div>
            <div class="card-body">
                <p>
                    The System name must be a standard string, no special characters are allowed.
                </p>
                <p>
                    For the Customer Information, selet the information that should be gathered for each customer that the system is assigned to.  If the information is not in the pull down list, you can type it in the search bar and click on it to add the information as an option.
                </p>
                <p>
                    To re-order the information, drag the existing options up or down.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
