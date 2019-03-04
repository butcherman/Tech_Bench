{!! Form::open(['route' => 'links.moveFile', 'id' => 'move-file-form']) !!}
    <input type="hidden" name="file_id" id="file_id" />
    <input type="hidden" name="cust_id" id="cust_id" />
    {{ Form::bsText('name', 'File Name') }}
    {{ Form::bsSelect('file_type_id', 'File Type', $fileTypes) }}
    {{ Form::bsCheckbox('remove', 'Remove From Link List') }}
    {{ Form::bsSubmit('Move File') }}
{!! Form::close() !!}
