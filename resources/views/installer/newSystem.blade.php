@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">New System</li>
</ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Add New System</h1></div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session()->has('success'))
            <div class="alert alert-success"><h3 class="text-center">{!!session('success')!!}</h3></div>
        @endif
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <new-system-form
            cat_route="{{route('installer.categories.show', 'get_all')}}"
            dropdown="{{json_encode($dropDown)}}"
            submit_route="{{route('installer.systems.store')}}"
            finish_route="{{route('admin.index')}}"
        ></new-system-form>
    </div>
</div>
<div class="row justify-content-center pad-top">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center"><strong>Instructions</strong></div>
            <div class="card-body">
                <p>
                    A new System will allow for documentation and software to be saved and downloaded later.  You can also attach a system to a customer and store information specific to that system for the customer.  The system will display in the navigation menu to the left.
                </p>
                <p>
                    The System name must be a standard string, no special characters are allowed.
                </p>
                <p>
                    For the Customer Information, selet the information that should be gathered for each customer that the system is assigned to.  If the information is not in the pull down list, you can type it in the search bar and click on it to add the information as an option.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
