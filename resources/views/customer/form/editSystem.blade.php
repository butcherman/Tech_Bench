@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <h5 class="text-center">Error: {{ $error }}</h5>
        @endforeach
    </div>
@endif
{!! Form::open(['route' => ['customer.systems.update', $id], 'id' => 'edit-system-form']) !!}
    @method('PUT')
    {{ Form::bsText('sysType', 'System Name', $sysName, ['disabled']) }}
    @foreach($sysFields as $field)
        {{ Form::bsText('field['.$field->data_type_id.']', $field->name, $field->value) }}
    @endforeach
    {{ Form::bsSubmit('Update System') }}
{!! Form::close() !!}
<div class="row justify-content-center pad-top">
    <div class="col-4">
        <button class="btn btn-warning btn-block" id="delete-system" data-id="{{$id}}">Delete System</button>
    </div>
</div>
