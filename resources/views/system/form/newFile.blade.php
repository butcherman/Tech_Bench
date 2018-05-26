<div id="form-errors" class="alert alert-danger d-none text-center"></div>
{!! Form::open([ 'route' => 'system.files.store', 'enctype' => 'multipart/form-data', 'id' => 'system-file-upload-form', 'files' => true]) !!}
    {{ Form::hidden('fileType', '', ['id' => 'fileType']) }}
    {{ Form::hidden('category', '', ['id' => 'category']) }}
    {{ Form::hidden('system', '', ['id' => 'system']) }}
    {{ Form::bsText('name', 'Name', null, ['required']) }}
    @include('_inc.drop1File')
    {{ Form::bsTextarea('description', 'Description') }}
    {{ Form::bsSubmit('Submit File') }}
{!! Form::close() !!}
