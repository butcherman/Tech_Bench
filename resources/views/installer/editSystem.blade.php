@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col grid-margin">
        <h4>Edit {{$system->name}}</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <edit-equipment-form
                    :sys_data="{{json_encode($system)}}"
                    :data_list="{{json_encode($dataList)}}"
                ></edit-equipment-form>
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
                    The System name must be a standard string, no special characters are allowed.
                </p>
                <p>
                    For the Customer Information, selet the information that should be gathered for each customer that
                    the system is assigned to.  If the information is not in the pull down list, you can type it in the
                    search bar and click on it to add the information as an option.
                </p>
                <p>
                    You can adjust the existing information order by dragging each item up and down.  If you would like 
                    to re-order the new information, submit it first, and then come back to re-order it.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
