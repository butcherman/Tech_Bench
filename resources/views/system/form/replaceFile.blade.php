<div id="form-errors" class="alert alert-danger d-none text-center"></div>
{!! Form::open(['route' => ['system.files.replace', $fileID], 'enctype' => 'multipart/form-data', 'id' => 'system-file-replace-form', 'files' => true ]) !!}
    @include('_inc.drop1File')
    {{ Form::bsSubmit('Replace File') }}
{!! Form::close() !!}
<div class="row justify-content-center pad-top">
    <div class="col-sm-12">
        <h3>Note:</h3>
        <p>This section is only for replacing an existing file with an updated version of the same file.</p>
    </div>
</div>
