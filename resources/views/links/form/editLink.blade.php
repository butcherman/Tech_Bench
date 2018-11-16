<div class="row justify-content-center">
    <div class="col-md-8">
        {!! Form::model($data, ['route' => ['links.details.update', $data->link_id], 'id' => 'edit-file-link-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
            @method('PUT')
            {{ Form::bsText('link_name', 'Link Name', null, ['required']) }}
            {{ Form::bsDate('expire', 'Expires On', null, ['required']) }}
            <div class="row justify-content-center">
                <div class="col-12">
                    <label class="switch">
                        <input type="checkbox" name="allowUp" {{$data->allow_upload ? 'checked' : ''}}>
                        <span class="slider round"></span>
                    </label>
                    Allow User to Upload Files 
                </div>
            </div>
            {{ Form::bsSubmit('Update File Link') }}
        {!! Form::close() !!}
    </div>
</div>
