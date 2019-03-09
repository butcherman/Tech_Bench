@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>New File Link</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!! Form::open(['route' => 'links.data.store', 'id' => 'new-file-link-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
                {{ Form::bsText('name', 'Link Name', null, ['placeholder' => 'Enter A Descriptive Name', 'required', 'autofocus']) }}
                {{ Form::bsDate('expire', 'Expires On', date('Y-m-d', strtotime('+30 days')), ['required']) }}
{{--
                <div class="form-group">
                    <label for="customer-tag">Link to Customer:</label>
                    <div class="input-group">
                        <input class="form-control border-right-0 border" type="search" id="customer-tag" name="customer_tag" placeholder="Enter Customer Number or Click Search Icon (Optional)" autocomplete="off" />
                        <span class="input-group-append" id="search-for-customer">
                            <button class="btn btn-outline-secondary border-left-0 border" id="search-for-customer-button" type="button" tabindex="-1">
                            <i class="fa fa-search"></i>
                        </button>
                        </span>
                    </div>
                </div>
--}}
                <div class="row justify-content-center">
                    <div class="col-5">
                        <label class="switch">
                            <input type="checkbox" name="allowUp" checked>
                            <span class="slider round"></span>
                        </label>
                        Allow User to Upload Files 
                    </div>
                </div>
{{--                @include('_inc.dropMultiFile')--}}
                {{ Form::bsSubmit('Create File Link') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
