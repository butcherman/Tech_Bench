<div id="form-errors" class="alert alert-danger d-none text-center"></div>
{!! Form::open(['route' => 'customer.files.store', 'id' => 'new-file-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
    {{ Form::hidden('custID', null, ['id' => 'custID']) }}
    {{ Form::bsText('name', 'File Name', null, ['required']) }}
    {{ Form::bsSelect('type', 'File Type', $fileTypes) }}
    @include('_inc.drop1File')
    {{ Form::bsSubmit('Upload File', ['id' => 'submit-file-button']) }}
{!! Form::close() !!}
