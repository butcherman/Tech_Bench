require('./bootstrap');
require('jquery-easing');
require('datatables.net-bs4');
//require('clipboard');

$(document).ready(function()
{
    //  Enable any tooltips on the page
    $('[data-tooltip="tooltip"]').tooltip();
    //  Disable autodiscover for Dropzone
    Dropzone.autoDiscover = false;
    
/////////////////////////// Drag and Drop/File Upload Functions ////////////////////////////////////
    //  Initialize drag and drop for only one file
    function fileDrop(form)
    {
        //  Initialize Drag and Drop            
        var drop = $('#dropzone-box').dropzone(
        {
            url: form.attr('action'),
            autoProcessQueue: false,
            init: function()
            {
                var myDrop = this;
                form.on('submit', function(e, formData)
                {
                    e.preventDefault();
                    myDrop.processQueue();
                });
                this.on('sending',  function(file, xhr, formData)
                {
                    var formArray = form.serializeArray();
                    $.each(formArray, function()
                    {
                        formData.append(this.name, this.value);
                    });
                });
                this.on('success', function(files, response)
                {    
                    uploadComplete(response);
                });
                this.on('error', function(file, response)
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
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            init: function()
            {
                var myDrop = this;
                form.on('submit', function(e, formData)
                {
                    e.preventDefault();
//                    myDrop.processQueue();
                    if(myDrop.getQueuedFiles().length > 0)
                    {
                        myDrop.processQueue();
                    }
                    else
                    {
                        $.post(form.attr('action'), form.serialize(), function(data)
                        {
                            uploadComplete(data);
                        });
                    }
                });
                this.on('sendingmultiple',  function(file, xhr, formData)
                {
                    var formArray = form.serializeArray();
                    $.each(formArray, function()
                    {
                        formData.append(this.name, this.value);
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
    window.resetEditModal = resetEditModal;
    window.fileDrop = fileDrop;
    window.multiFileDrop = multiFileDrop;
});













