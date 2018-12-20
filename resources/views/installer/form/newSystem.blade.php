@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item"><a href="{{route('installer.systems.index')}}">Add System</a></li>
    <li class="breadcrumb-item active">{{$cat}}</li>
</ol>
@endsection

@section('content')
<div class="d-none">
    <div class="form-group" id="select-option-div">
        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
            <option></option>
            @foreach($dropDown as $drop)
                <option value="{{$drop->name}}">{{$drop->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Add New System for {{$cat}}</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session()->has('success'))
                <div class="alert alert-success">{!!session('success')!!}</div>
            @endif
            @if($errors->any())
                @foreach($errors->all() as $err)
                    <div class="alert alert-danger">
                        <h2 class="text-center">{{$err}}</h2>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!!Form::open(['route' => ['installer.submitSys', $cat]])!!}
                <fieldset>
                    {{Form::bsText('name', 'System Name', null, ['placeholder' => 'Enter Descriptive Name', 'required'])}}
                </fieldset>
                <fieldset id="system-customer-information">
                    <label>Customer Information to Gather</label>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="custField[]" class="form-control pad-bottom customer-field" placeholder="Select An Option">
                            <option></option>
                            @foreach($dropDown as $drop)
                                <option value="{{$drop->name}}">{{$drop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>
                {{Form::bsSubmit('Create System')}}
            {!!Form::close()!!}
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
                        For the Customer Information, selet the information that should be gathered for each customer that the system is assigned to.  If the information is not in the pull down list, you can type it in the search bar and click &quot;new.&quot;
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    selectInit();
    function selectInit()
    {
        $('.customer-field').select2(
        {
            placeholder: 'Select An Option',
            tags: true,
            createTag: function (params) 
            {
                return {
                    id: params.term,
                    text: params.term,
                    newOption: true
                }
            },
            templateResult: function (data) 
            {
                var $result = $("<span></span>");
                $result.text(data.text);
                if (data.newOption) 
                {
                    $result.append(" <em>(new)</em>");
                }
                return $result;
            }
        });
    }
</script>
@endsection
