@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>New Tech Tip</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <h5 class="text-center">Error: {{ $error }}</h5>
                    @endforeach
                </div>
            @endif
            {!! Form::model($data, ['route' => ['tip.id.update', $data->tip_id], 'id' => 'edit-tech-tip-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
                @method('PUT')
                {{ Form::bsText('subject', 'Subject', null, ['placeholder' => 'Enter A Descriptive Subject', 'required', 'autofocus']) }}
                <div class="row">
                    <div class="col-6">
                        {{ Form::bsSelect('sysType', 'Tag A System', $sysToTag, null, ['placeholder' => 'Select A System To Tag']) }}
                    </div>
                    <div class="col-6">
                        <label for="tags">System Tags:</label>
                        <div id="tags" class="form-control tech-tip-tag-wrapper" required>
                            @foreach($systems as $sys)
                                <div class="tech-tip-tag" name="tipTag[]" data-value="{{$sys->sys_id}}">{{$sys->name}} <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            @endforeach
                        </div>
                        <span id="tag-error" class="text-danger d-none">Select At Least One System to Tag</span>
                    </div>
                </div>
                {{ Form::bsTextarea('description', 'Tech Tip', null, ['rows' => '15']) }}
                <h4>Attachements:</h4>
                @if(!$files->isEmpty())
                    <ul class="list-group">
                        @foreach($files as $file)
                            <li class="list-group-item">
                                <a href="{{route('downloadPage', ['id' => $file->file_id, 'filename' => $file->file_name])}}">{{$file->file_name}}</a><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {{ Form::hidden('existingFile[]', $file->file_id) }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center">No Attachements</p>
                @endif
                @include('_inc.dropMultiFile')
                {{ Form::bsSubmit('Update Tech Tip') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function()
{        
    //  Initialize Tinymce
    tinymce.init(
    {
        selector: 'textarea',
        height: '400',
        plugins: 'autolink table'
    });
    //  Initialize Drag and Drop
    techTipDrop($('#edit-tech-tip-form'));
    //  Add a system tag
    $('#sysType').on('change', function()
    {
        var value = $(this).val();
        var name  = $(this).find('option:selected').text();
        var divVal = '<div class="tech-tip-tag" name="tipTag[]" data-value="'+value+'">'+name+' <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $('#tags').append(divVal);
        $('#tag-error').addClass('d-none');
    });
    //  Remove a system tag
    $(document).on('click', '.close', function()
    {
        $(this).parent().remove();
    });
});
    
    //  Special drag and drop function for tech tips - includes additional validation
    function techTipDrop(form)
    {
        //  Initialize Drag and Drop            
        var drop = $('#dropzone-box').dropzone(
        {
            url: form.attr('action'),
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            init: function()
            {
                var myDrop = this;
                form.on('submit', function(e, formData)
                {
                    e.preventDefault();
                    e.stopPropagation();
                    if($('.tech-tip-tag-wrapper').children().length == 0)
                    {
                        $('#tag-error').removeClass('d-none');
                    }
                    else
                    {
                        if(myDrop.getQueuedFiles().length > 0)
                        {
                            myDrop.processQueue();
                        }
                        else
                        {
                            tinymce.triggerSave();
                            $('.tech-tip-tag').each(function()
                            {
                                $('<input />').attr('type', 'hidden')
                                    .attr('name', 'sysTags[]')
                                    .attr('value', $(this).data('value'))
                                    .appendTo(form);
                            });
                            $.post(form.attr('action'), form.serialize(), function(data)
                            {
                                uploadComplete(data);
                            });
                        }
                    }
                });
                this.on('sendingmultiple',  function(file, xhr, formData)
                {
                    var formArray = form.serializeArray();
                    tinymce.triggerSave();
                    $.each(formArray, function()
                    {
                        formData.append(this.name, this.value);
                    });
                    $('.tech-tip-tag').each(function()
                    {
                        formData.append('sysTags[]', $(this).data('value'));
                    });
                });
                this.on('successmultiple', function(files, response)
                {    
                    uploadComplete(response);
                });
                this.on('errormultiple', function(file, response)
                {
                    uploadFailed(response);
                });
            }
        });
    }
    
    function uploadComplete(res)
    {
        if($.isNumeric(res))
        {
            url = '{{ route('tip.details', ['id' => ':id', 'subj' => ':sub']) }}';
            url = url.replace(':id', res);
            url = url.replace(':sub', $('#subject').val());
            window.location.replace(url);
        }
        else
        {
            uploadFailed(res);
        }
    }
    function uploadFailed(res)
    {
        var msg = 'There was a problem updating the Tech Tip.\nPlease contact the system administrator';
        alert(msg+res);
        console.log(res);
    }
</script>
@endsection
