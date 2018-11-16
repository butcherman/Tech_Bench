{!! Form::open(['route' => ['links.submitAdd', $id],'id' => 'add-file-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
    @include('_inc.dropMultiFile')
    {{ Form::bsSubmit('Add File') }}
{!! Form::close() !!}
