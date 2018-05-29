{!! Form::open(['route' => 'customer.notes.store', 'id' => 'new-note-form']) !!}
    {{ Form::hidden('custID', null, ['id' => 'custID'])}}
    {{ Form::bsText('subject', 'Note Subject', null, ['placeholder' => 'Subject', 'required']) }}
    {{ Form::bsTextarea('note', 'Description', null, ['rows' => '15']) }}
    {{ Form::bsCheckbox('urgent', 'Mark As Urgent') }}
    {{ Form::bsSubmit('Add Note') }}
{!! Form::close() !!}
