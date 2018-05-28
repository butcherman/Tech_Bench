require('./bootstrap');
require('jquery-easing');

$(document).ready(function()
{
    //  Enable any tooltips on the page
    $('[data-toggle="tooltip"]').tooltip();
    //  Disable autodiscover for Dropzone
    Dropzone.autoDiscover = false;
    
/////////////////////////// Drag and Drop/File Upload Functions ////////////////////////////////////
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
});
