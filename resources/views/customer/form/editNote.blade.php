{!! Form::model($note, ['route' => ['customer.notes.update', $noteID], 'id' => 'edit-note-form']) !!}
    @method('PUT')
    {{ Form::bsText('subject', 'Note Subject', null, ['placeholder' => 'Subject', 'required']) }}
    {{ Form::bsTextarea('description', 'Description', null, ['rows' => '15']) }}
    {{ Form::bsCheckbox('urgent', 'Mark As Urgent', 'on', $note->urgent ? true : false) }}
    {{ Form::bsSubmit('Update Note') }}
{!! Form::close() !!}
