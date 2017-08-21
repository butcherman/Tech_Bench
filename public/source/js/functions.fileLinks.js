//  Load tooltips
$('body').tooltip({
    selector: '[data-tooltip="tooltip"]', 
    trigger: 'hover'
});

//  Function to load custom notes
function loadInstruction()
{
    if (typeof tinymce != 'undefined' && tinymce != null) {
        tinymce.remove();
    }
    $('#custom-note').load('/links/loadInstructions/'+linkID);
    tinymce.init(
    { 
        selector:'textarea',
        height: '400',
        plugins: 'placeholder'
    });
}

$('#customNote').validate(
{
    submitHandler: function()
    {
        tinymce.triggerSave();
        $.post('/links/submitInstructions/'+linkID, $('#customNote').serialize(), function(data)
        {
            if(data === 'success')
            {
                loadInstruction();
            }
            else
            {
                alert('There was an issue saving the note');
            }
        });
    }
});

//  Delete an existing upload link along with all related files
$(document).on('click', '.delete-link', function()
{
    var linkID = $(this).data('link');
    $(this).attr('data-selected', 'selected');
    $('#modal-body').load('/home/yesorno', function()
    {
        $('.select-yes').addClass('delete-link-confirm');
        $('.select-yes').attr('data-link', linkID);
    });
});
//  Confirm to delete the link
$(document).on('click', '.delete-link-confirm', function()
{
    $('#modal-body').html('<img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />');
    $.get('/links/deleteLink/'+$(this).data('link'), function(data)
    {
        $('#edit-modal').modal('hide');
        if(data === 'success')
        {
            $('[data-selected]').closest('tr').remove();
        }
        else
        {
            alert(data);
        }
    });
});

//  Modify the details of an existing link
$('#edit-link').on('click', function()
{
    $('#modal-header').text('Edit Link');
    $('#modal-body').load('/links/editLinkForm/'+linkID, function()
    {
        $('#allowUpload').bootstrapToggle();
    });
    
});
//  Submit the edit link form
$(document).on('click', '#updateLink', function()
{
    $('#edit-file-link').validate(
    {
        submitHandler: function()
        {
            $.post('/links/editLinkSubmit/'+linkID, $('#edit-file-link').serialize(), function(data)
            {
                var allowed = $('#allowUpload').is(':checked') ? 'Yes' : 'No';
                if(data === 'success')
                {
                    $('#link-name').text($('#linkName').val());
                    $('#link-expire').text($('#expire').val());
                    $('#link-allow').text(allowed);
                    $('#edit-modal').modal('hide');
                }
                else
                {
                    alert('There was an issue updating the link data');
                }
            });
        }
    });
});

//  Delete an active file link
$('#delete-link').on('click', function()
{
    var linkID = $(this).data('link');
    $(this).attr('data-selected', 'selected');
    $('#modal-body').load('/home/yesorno', function()
    {
        $('.select-yes').addClass('delete-link-confirm-active');
        $('.select-yes').attr('data-link', linkID);
    });
});
//  Confirm to delete the link
$(document).on('click', '.delete-link-confirm-active', function()
{
    $('#modal-body').html('<img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />');
    $.get('/links/deleteLink/'+linkID, function(data)
    {
        if(data === 'success')
        {
            window.location.replace('/links/');
        }
        else
        {
            alert(data);
        }
    });
});

//  Function to load all files uploaded by the user
function userUploadedFiles()
{
    $('#user-uploaded-files').load('/links/userUploadedFiles/'+linkID);
}
//  Function to load all files uploaded by a customer/visitor
function customerUploadedFiles()
{
    $('#customer-uploaded-files').load('/links/customerUploadedFiles/'+linkID);
}

//  Add file to the existing link
$('#add-file').on('click', function()
{
    $('#modal-header').text('Add New File');
    $('#modal-body').load('/links/newFileForm');
});
//  Validate the new file form
$(document).on('click', '#submit-add-file', function()
{
    $('#add-file-form').validate(
    {
        rules:
        {
            file:
            {
                filesize: maxFile
            }
        },
        messages:
        {
            file: "File size must be less than "+filesize(maxFile)
        },
        submitHandler: function()
        {
            submitFile('/links/newFileSubmit/'+linkID, 'add-file-form');
        }
    });
});
//  Upload finished - refresh "Uploaded By Me" section to include new files
function uploadComplete(res)
{
    userUploadedFiles();
    $('#edit-modal').modal('hide');
    $('#modal-body').html('');
}

//  Delete a file
$(document).on('click', '.delete-file-link', function()
{
    var fileID = $(this).data('fileid');
    $('#modal-header').text('Confirm Delete File');
    $('#modal-body').load('/home/yesorno', function()
    {
        $('.select-yes').addClass('delete-file-confirm');
        $('.select-yes').attr('data-fileid', fileID);
    });
});
//  Confirm to delete the file
$(document).on('click', '.delete-file-confirm', function()
{
    $.get('/links/deleteFile/'+$(this).data('fileid'), function(data)
    {
        if(data === 'success')
        {
            userUploadedFiles();
            customerUploadedFiles();
            $('#edit-modal').modal('hide');
        }
        else
        {
            alert(data);
        }
    });
});

//  View a note added to a file
$(document).on('click', '.view-note-link', function()
{
    var noteID = $(this).data('noteid');
    $('#modal-header').text('File Note');
    $('#modal-body').load('/links/loadNote/'+noteID);
});

//  Bring up form to share the link with another user
$('#share-link').on('click', function()
{
    $('#modal-header').text('Share File Link');
    $('#modal-body').load('/links/shareLinkForm');
});

$(document).on('click', $('#submit-share-form'), function()
{
    $('#share-form').validate(
    {
        submitHandler: function()
        {
            $.post('/links/shareLinkSubmit/'+linkID, $('#share-form').serialize(), function(data)
            {
                if(data === 'success')
                {
                    $('#edit-modal').modal('hide');
                }
                else
                {
                    alert('Sorry, there was an error processing your request');
                }
            });
        }
    });
});

