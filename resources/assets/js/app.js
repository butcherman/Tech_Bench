/** global: maxUpload */
//  Third Party Libraries
require('./bootstrap');
require('jquery-easing');
require('datatables.net-bs4');
require('dropzone');
require('select2');
require('fastselect');
//require('clipboard');

$(document).ready(function()
{
    //  Enable any tooltips on the page
    initTooltip();
});

//  Initialize any tooltips on the page
function initTooltip()
{
    $('[data-tooltip="tooltip"]').tooltip();
}

/////////////////////////// Drag and Drop/File Upload Functions ////////////////////////////////////
//  Initialize drag and drop for only one file
function fileDrop(form)
{
    //  Initialize Drag and Drop            
    var drop = $('#dropzone-box').dropzone(
    {
        url: form.attr('action'),
        autoProcessQueue: false,
        parallelUploads: 1,
        maxFiles: 1,
        maxFilesize: maxUpload,
        addRemoveLinks: true,
        chunking: true,
        chunkSize: 1000000,
        parallelChunkUploads: false,
        method: "POST",
        init: function()
        {
            var myDrop = this;
            form.on('submit', function(e, formData)
            {
                e.preventDefault();
                if(myDrop.getQueuedFiles().length > 0)
                {
                    myDrop.processQueue();
                    $('#forProgressBar').show();
                    $('.submit-button').attr('disabled', true);
                }
                else
                {
                    $.post(form.attr('action'), form.serialize(), function(data)
                    {
                        uploadComplete(data);
                    });
                }
            });
            this.on('sending', function(file, xhr, formData)
            {
                var formArray = form.serializeArray();
                $.each(formArray, function()
                {
                    formData.append(this.name, this.value);
                }); 
            });
            this.on('uploadprogress', function(progress)
            {
                var prog = Math.round(progress.upload.progress);
                    
                if(prog != 100)
                {
                    $("#progressBar").css("width", prog+"%");
                    $("#progressStatus").text(prog+"%");
                }
//                $("#progressBar").css("width", Math.round(progress.upload.progress)+"%");
//                $("#progressStatus").text(Math.round(progress.upload.progress)+"%");
            });
            this.on('reset', function()
            {
                $('#form-errors').addClass('d-none');
            });
            this.on('success', function(files, response)
            {
                console.log(response);
                uploadComplete(response);
            });
            this.on('errormultiple', function(file, response)
            {
                uploadFailed(response);
            });
        }
    });
}

//  Initialize drag and drop for multiple file uploads
function multiFileDrop(form)
{
    //  Initialize Drag and Drop            
    var drop = $('#dropzone-box').dropzone(
    {
        url: form.attr('action'),
        autoProcessQueue: false,
        parallelUploads: 1,
        maxFiles: 1,
        maxFilesize: maxUpload,
        addRemoveLinks: true,
        chunking: true,
        chunkSize: 1000000,
        parallelChunkUploads: false,
        method: "POST",
        init: function()
        {
            var myDrop = this;
            form.on('submit', function(e, formData)
            {
                e.preventDefault();
                if(myDrop.getQueuedFiles().length > 0)
                {
                    myDrop.processQueue();
                    $('#forProgressBar').show();
                    $('.submit-button').attr('disabled', true);
                }
                else
                {
                    $.post(form.attr('action'), form.serialize(), function(data)
                    {
                        uploadComplete(data);
                    });
                }
            });
            this.on('sending', function(file, xhr, formData)
            {
                var formArray = form.serializeArray();
                $.each(formArray, function()
                {
                    formData.append(this.name, this.value);
                }); 
            });
            this.on('uploadprogress', function(progress)
            {
                var prog = Math.round(progress.upload.progress);
                    
                if(prog != 100)
                {
                    $("#progressBar").css("width", prog+"%");
                    $("#progressStatus").text(prog+"%");
                }
//                $("#progressBar").css("width", Math.round(progress.upload.progress)+"%");
//                $("#progressStatus").text(Math.round(progress.upload.progress)+"%");
            });
            this.on('reset', function()
            {
                $('#form-errors').addClass('d-none');
            });
            this.on('success', function(files, response)
            {
                console.log(response);
                uploadComplete(response);
            });
            this.on('errormultiple', function(file, response)
            {
                uploadFailed(response);
            });
        }
    });
}

//  Reset the progress bar and drag and drop box
function resetProgressBar()
{
    $('#dragndrop-notice').text('Or Drag Files Here');
    $('#progressBar').css('width', '0%').attr('aria-valuenow', 0);
    $('#progressStatus').text('');
    $('#forProgressBar').hide();
}

//  Reset the Edit Modal to its default state
function resetEditModal()
{
    $('#edit-modal').modal('hide');
    $('#edit-modal').find('.modal-title').text('');
    $('#edit-modal').find('.modal-body').text('');
}

//////////////////////////////////////////////////////////////////////////////////////////

//  Make the functions in this file globally accessable
window.resetProgressBar = resetProgressBar;
window.resetEditModal   = resetEditModal;
window.fileDrop         = fileDrop;
window.multiFileDrop    = multiFileDrop;
