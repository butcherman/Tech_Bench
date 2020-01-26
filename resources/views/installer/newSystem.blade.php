@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col grid-margin">
        <h4>Add New System for {{$cat->name}}</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <new-equipment-form 
                    cat_id="{{$cat->cat_id}}"
                    :data_list="{{json_encode($dataList)}}"   
                ></new-equipment-form>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-header text-center"><strong>Instructions</strong></div>
            <div class="card-body">
                <p>
                    A new System will allow for documentation and software to be saved and downloaded later.  
                    You can also attach a system to a customer and store information specific to that system for the customer.  
                </p>
                <p>
                    The System name must be a standard string, no special characters are allowed.
                </p>
                <p>
                    For the Customer Information, selet the information that should be gathered for each customer that 
                    the system is assigned to.  If the information is not in the pull down list, you can type it in the 
                    search bar and click on it to add the information as an option.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection