@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>New File Link</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!! Form::open(['route' => 'links.details.store', 'id' => 'new-file-link-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
                {{ Form::bsText('name', 'Link Name', null, ['required']) }}
                {{ Form::bsDate('expire', 'Expires On', date('Y-m-d', strtotime('+30 days')), ['required']) }}
                @include('_inc.dropMultiFile')
                <div class="row justify-content-center">
                    <div class="col-5">
                        <label class="switch">
                            <input type="checkbox" name="allowUp" checked>
                            <span class="slider round"></span>
                        </label>
                        Allow User to Upload Files 
                    </div>
                </div>
                {{ Form::bsSubmit('Create File Link') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function()
{
    //  Initialize Drag and Drop
    multiFileDrop($('#new-file-link-form'));
});
    
function uploadComplete(res)
{
    if($.isNumeric(res))
    {
        url = '{{ route('links.info', ['id' => ':id', 'subj' => ':sub']) }}';
        url = url.replace(':id', res);
        url = url.replace(':sub', $('#name').val());
        window.location.replace(url);
    }
    else
    {
        uploadFailed(res);
    }
}
function uploadFailed(res)
{
    var msg = 'There was a problem adding the Tech Tip.\nPlease contact the system administrator';
    alert(msg+res);
}
</script>
@endsection
