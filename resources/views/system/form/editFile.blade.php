<div id="form-errors" class="alert alert-danger d-none text-center"></div>
{!! Form::model($data, ['route' => ['system.files.update', $data->sys_file_id], 'id' => 'system-file-edit-form']) !!}
    @method('PUT')
    {{ Form::bsText('name', 'Name', null, ['required']) }}
    {{ Form::bsTextarea('description', 'Description') }}
    {{ Form::bsSubmit('Update File Information') }}
{!! Form::close() !!}
