@foreach($sysFields as $field)
    {{ Form::bsText('field['.$field->data_type_id.']', $field->name) }}
@endforeach
