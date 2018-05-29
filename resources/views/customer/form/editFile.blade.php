<div id="form-errors" class="alert alert-danger d-none text-center"></div>
{!! Form::model($file, ['route' => ['customer.files.update', $fileID], 'id' => 'edit-file-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
    @method('PUT')
    {{ Form::bsText('name', 'File Name') }}
    {{ Form::bsSelect('file_type_id', 'File Type', $fileTypes) }}
    {{ Form::bsSubmit('Edit File', ['id' => 'submit-file-button']) }}
{!! Form::close() !!}
