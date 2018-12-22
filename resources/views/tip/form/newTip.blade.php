@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tip.index')}}">Tech Tips</a></li>
    <li class="breadcrumb-item active">New Tech Tip</li>
</ol>
@endsection

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
            {!! Form::open(['route' => 'tip.id.store', 'id' => 'new-tech-tip-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
                {{ Form::bsText('subject', 'Subject', null, ['placeholder' => 'Enter A Descriptive Subject', 'required', 'autofocus']) }}
                <div class="row">
                    <div class="col-6">
                        {{ Form::bsSelect('sysType', 'Tag A System', $systems, null, ['placeholder' => 'Select A System To Tag']) }}
                    </div>
                    <div class="col-6">
                        <label for="tags">System Tags:</label>
                        <div id="tags" class="form-control tech-tip-tag-wrapper" required></div>
                        <span id="tag-error" class="text-danger d-none">Select At Least One System to Tag</span>
                    </div>
                </div>
                {{ Form::bsTextarea('details', 'Tech Tip', null, ['rows' => '15']) }}
                @include('_inc.dropMultiFile')
                {{ Form::bsSubmit('Create Tech Tip') }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@include('_inc.modal')
@endsection

@section('script')
<script>
$(document).ready(function()
{
    //  Initialize Tinymce
    tinymce.init(
    {
        selector: 'textarea',
        height: 500,
        plugins: 'advlist autolink lists link image table',
        relative_urls: false,
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '{{ route('tip.processImage') }}',
        file_picker_types: 'image',
        file_picker_callback: function(cb, value, meta) 
        {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () 
                {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
            };
            input.click();
        }
    });
    //  Initialize Drag and Drop
    techTipDrop($('#new-tech-tip-form'));
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
            maxFilesize: maxUpload,
            addRemoveLinks: true,
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
                            $('#forProgressBar').show();
                            myDrop.processQueue();
                        }
                        else
                        {
                            $('#edit-modal').modal('show');
                            $('#edit-modal').find('.modal-dialog').html('<div class="jumbotron text-center h-50"><i class="fa fa-circle-o-notch fa-spin fa-5x" aria-hidden="true"></i></div>');
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
                this.on('totaluploadprogress', function(progress)
                {
                    $("#progressBar").css("width", Math.round(progress)+"%");
                    $("#progressStatus").text(Math.round(progress)+"%");
                    console.log(progress);
                });
                this.on('reset', function()
                {
                    $('#form-errors').addClass('d-none');
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
        var msg = 'There was a problem adding the Tech Tip.\nPlease contact the system administrator';
        alert(msg+res);
    }
</script>
@endsection
