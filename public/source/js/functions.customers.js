//  Load tooltips
$('body').tooltip({
    selector: '[data-tooltip="tooltip"]', 
    trigger: 'hover'
});
//  Load the System information from the database
$('#customer-system-information').load('/customer/loadSystems/'+custID);
//  Load the Contact information from the database
$('#contact-information').children('tbody').load('/customer/loadContacts/'+custID);
//  Load the customer notes
$('#notes-wrapper').load('/customer/loadNotes/'+custID);
//  Load the customer files
$('#customer-files-table').children('tbody').load('/customer/loadFiles/'+custID);

//  Clear any data in the Modal body when the Modal closes
$('#edit-modal').on('hidden.bs.modal', function()
{
    $('#modal-header').html('Modal Dialog');
    $('#modal-body').html('');
});

//  Add or remove a bookmark for a customer
$('#customer-fav').click(function()
{
    if($(this).hasClass('item-fav-unchecked'))
    {
        $(this).removeClass('item-fav-unchecked');
        $(this).addClass('item-fav-checked');
    }
    else
    {
        $(this).removeClass('item-fav-checked');
        $(this).addClass('item-fav-unchecked');
    }

    $.get('/customer/toggleFav/'+custID);
});

//  Load the "Edit Customer" Modal Dialog
$('#customer-edit').on('click', function()
{
    $('#modal-header').html('Edit Customer');
    $('#modal-body').load('/customer/editCustDataLoad/'+custID);
});

//  Validate the customer edit form
$(document).on('click', '#submit-cust-edit', function(e)
{
    $('#customer-edit-form').validate(
    {
        rules:
        {
            custName: "required",
            custAddr: "required",
            custCity: "required",
            zipCode: "required"
        },
        submitHandler: function()
        {
            $.post('/customer/editCustDataSubmit/'+custID, $('#customer-edit-form').serialize(), function(data)
            {
                if(data === 'success')
                {
                    //  Load the new customer information into the web page
                    var name = $('#custName').val();
                    var dba  = $('#custDBA').val();
                    var addr = $('#custAddr').val()+',<br />'+$('#custCity').val()+' '+$('#state').val()+' '+$('#zipCode').val();
                    var link = $('#custAddr').val()+', '+$('#custCity').val()+' '+$('#state').val()+' '+$('#zipCode').val();
                    
                    $('#name-span').html(name);
                    $('#dba-span').html(dba);
                    $('#addr-span').html(addr);
                    $('#addr-span').attr('href', 'https://maps.google.com/?q='+link);
                    
                    $('#edit-modal').modal('hide');
                }
                else
                {
                    alert('Sorry, there was an issue processing your request.\nA log has been generated.');
                }
            });
        }
    });
});

//  Load the modal to edit the system information
$('#edit-system-link').on('click', function()
{
    var system = $('.tab-content').children('.active').attr('id');
    $('#modal-header').html('Edit System');
    $('#modal-body').load('/customer/editCustSystemLoad/'+custID+'/'+system);
});

//  Submit the edit system form
$(document).on('click', '#editCustSystemSubmit', function(e)
{
    e.preventDefault();
    $.post('/customer/editCustSystemSubmit/'+custID, $(this).parents('form:first').serialize(), function(data)
    {
        $('#edit-modal').modal('hide');
        if(data == 'success')
        {
            $('#customer-system-information').load('/customer/loadSystems/'+custID);
        }
        else
        {
            alert('There Was A Problem Updating The System.  A log has been generated.');
        }
    });
});

//  Load the modal to add a new system
$('#add-system-link').on('click', function()
{
    $('#modal-header').html('Add System');
    $('#modal-body').load('/customer/newSystemLoad/'+custID);
});

//  Load the form based on the selected system
$(document).on('click', '#addSystemType', function()                    
{
    if($(this).val() == '')
    {
        $('#add-system-data').html('');
        $('#add-system-form input[type=submit]').attr('disabled', 'disabled');
    }
    else
    {
        $('#add-system-data').load('/system/loadSystemForm/'+$(this).val());
        $('#add-system-form input[type=submit]').removeAttr('disabled');
    }
});


//  Submit the new system form
$(document).on('click', '#add-system-submit', function(e)
{
    e.preventDefault();
    $.post('/customer/newSystemSubmit/'+custID, $(this).parents('form:first').serialize(), function(data)
    {
        if(data == 'success')
        {
            $('#edit-modal').modal('hide');
            $('#customer-system-information').load('/customer/loadSystems/'+custID);
        }
        else if(data == 'duplicate')
        {
            $('#add-system-data').html('<h4 class="text-center">This Customer Already Has This System Type</h4>');
        }
        else
        {
            alert('There was a problem adding the system.  A log has been generated');
        }
    });
});

//  Load the form to add a new contact
$('#add-contact-btn').on('click', function()
{
    $('#modal-header').html('Add Contact');
    $('#modal-body').load('/customer/newContactLoad');
});

//  Submit the form to add a new contact
$(document).on('click', '#submit-new-contact', function()
{
    $('#add-contact-form').validate(
    {
        rules:
        {
            contName: "required",
            contPhone: "phoneUS",
            contEmail: "email"
        },
        messages:
        {
            contName: "A Name Must Be Entered",
            contPhone: "Please Enter Valid Phone Number Including Area Code",
            contEmail: "Please Enter A Valid Email Address"
        },
        submitHandler: function()
        {
            $.post('/customer/newContactSubmit/'+custID, $('#add-contact-form').serialize(), function(data)
            {
                $('#edit-modal').modal('hide');

                if(data == 'success')
                {
                    $('#contact-information').children('tbody').load('/customer/loadContacts/'+custID);
                }
                else
                {
                    alert('There Was A Problem Adding Contact.')
                }
            });
        }
    });
});

//  Bring up the form to edit an existing contact
$(document).on('click', '.edit-contact-link', function()
{
    var linkID = $(this).data('contid');
    $('#modal-header').html('Edit Contact');
    $('#modal-body').load('/customer/editContactLoad/'+linkID);
});

//  Submit the for to edit an existing contact
$(document).on('click', '#submit-edit-contact', function()
{
    $('#edit-contact-form').validate(
    {
        rules:
        {
            contName: "required",
            contPhone: "phoneUS",
            contEmail: "email"
        },
        messages:
        {
            contName: "A Name Must Be Entered",
            contPhone: "Please Enter Valid Phone Number Including Area Code",
            contEmail: "Please Enter A Valid Email Address"
        },
        submitHandler: function()
        {
            $.post('/customer/editContactSubmit/'+$('#contID').val(), $('#edit-contact-form').serialize(), function(data)
            {
                $('#edit-modal').modal('hide');

                if(data == 'success')
                {
                    $('#contact-information').children('tbody').load('/customer/loadContacts/'+custID);
                }
                else
                {
                    alert('There Was A Problem Updating Contact.')
                }
            });
        }
    });
});

//  Delete an existing contact
$(document).on('click', '.delete-contact-link', function()
{
    var contact = $(this).data('contid');
    $('#modal-header').html('Delete Contact');
    $('#modal-body').load('/home/yesorno', function()
    {
        $('.select-yes').addClass('delete-contact');
        $('.select-yes').attr('data-contactid', contact);
    });
});

//  Confirm delete existing contact
$(document).on('click', '.select-yes.delete-contact', function()
{
    var contactID = $(this).data('contactid');
    $.get('/customer/deleteContactSubmit/'+contactID, function(data)
    {
        $('#edit-modal').modal('hide');
        if(data == 'success')
        {
            $('#contact-information').children('tbody').load('/customer/loadContacts/'+custID);
        }
        else
        {
            alert('There was a problem deleting the contact');
        }
    });
});

//  Open and close notes
$('#notes-wrapper').on('click', '.panel-heading', function()
{
    $(this).parent().parent().toggleClass('col-lg-12');
    $(this).parent().parent().toggleClass('panel-minimized');
});

//  Load the New Note form
$('#new-note-link').click(function()
{
    $('#modal-header').html('New Customer Note');
    $('#modal-body').load('/customer/newNoteForm', function()
    {
        tinymce.init(
        { 
            selector:'textarea',
            height: '400'
        });
    });
});

//  Validate and submit the new note form
$(document).on('click', '#submit-new-note-btn', function()
{
    $('#add-note-form').validate(
    {
        rules:
        {
            noteSubject: "required",
            noteDescription: 
            {
                required: true,
                minlength: 10
            }
        },
        messages:
        {
            noteSubject: "Please Give An Easy To Identify Subject",
            noteDescription: "Please Enter Some Information"
        },
        submitHandler: function()
        {
            tinymce.triggerSave();
            $.post('/customer/newNoteSubmit/'+custID, $('#add-note-form').serialize(), function(data)
            {
                $('#edit-modal').modal('hide');
                if(data == 'success')
                {
                    $('#notes-wrapper').load('/customer/loadNotes/'+custID);
                }
                else
                {
                    alert('There Was A Problem Adding Note.\nPlease Contact System Administrator');
                }
            });
        }
    });
});

//  Load the edit customer note form
$(document).on('click', '.edit-note', function()
{
    var noteID = $(this).data('noteid');
    $('#modal-header').html('Edit Customer Note');
    $('#modal-body').load('/customer/editNoteForm/'+noteID, function()
    {
        $('#edit-note-form').data('noteid', noteID);
        tinymce.init(
        { 
            selector:'textarea',
            height: '400'
        });
    });
});

//  Validate and submit the edit note form
$(document).on('click', '#submit-edit-note-btn', function()
{
    $('#edit-note-form').validate(
    {
        rules:
        {
            noteSubject: "required",
            noteDescription: 
            {
                required: true,
                minlength: 10
            }
        },
        messages:
        {
            noteSubject: "Please Give An Easy To Identify Subject",
            noteDescription: "Please Enter Some Information"
        },
        submitHandler: function()
        {
            var noteID = $('#edit-note-form').data('noteid');
            tinymce.triggerSave();
            $.post('/customer/editNoteSubmit/'+noteID, $('#edit-note-form').serialize(), function(data)
            {
                $('#edit-modal').modal('hide');
                if(data == 'success')
                {
                    $('#notes-wrapper').load('/customer/loadNotes/'+custID);
                }
                else
                {
                    alert('There Was A Problem Adding Note.\nPlease Contact System Administrator');
                }
            });
        }
    });
});

//  Load the new file form
$('#add-file-link').click(function()
{
    $('#modal-header').html('Add New File');
    $('#modal-body').load('/customer/newFileForm');
});

//  Validate and submit the new file form
$(document).on('click', '#new-file-submit-lnk', function()
{
    $('#new-file-form').validate(
    {
        rules:
        {
            fileName: "required",
            fileType: "required",
            custFile: "required",
            file:
            {
                filesize: maxFile
            }
        },
        messages:
        {
            fileName: "Please Enter A Valid Name To Identify The File",
            file: "File size must be less than 2Gb"
        },
        submitHandler: function()
        {
            submitFile('/customer/newFileSubmit/'+custID, 'new-file-form');
        }
    });
});

//  Copy the file name into the "name" field if left empty
$(document).on('change', '#file', function()
{
    var fileName = $('input[type=file]').val().split('\\').pop();
    if (!$("#fileName").val())
    {
        $("#fileName").val(fileName);
    }
});

//  Reload notes after a file has been uploaded
function uploadComplete(res)
{
    $('#edit-modal').modal('hide');
    if(res == '1')
    {
        $('#customer-files-table').children('tbody').load('/customer/loadFiles/'+custID);
    }
    else
    {
        alert('There was a problem uploading the file');
    }
}

//  Load the edit file form
$(document).on('click', '.edit-file-lnk', function()
{
    var fileID = $(this).data('fileid');
    $('#modal-header').html('Edit File');
    $('#modal-body').load('/customer/editFileLoad/'+fileID, function()
    {
        $('#edit-file-form').data('fileid', fileID);
    });
});

//  Submit the edit file form
$(document).on('click', '#edit-file-submit-lnk', function()
{
    $('#edit-file-form').validate(
    {
        rules:
        {
            fileName: "required",
            fileType: "required"
        },
        messages:
        {
            fileName: "Please Enter A Valid Name To Identify The File",
        },
        submitHandler: function()
        {
            var fileID = $('#edit-file-form').data('fileid');
            $.post('/customer/editFileSubmit/'+fileID, $('#edit-file-form').serialize(), function(data)
            {
                $('#edit-modal').modal('hide');
                if(data == 'success')
                {
                    $('#customer-files-table').children('tbody').load('/customer/loadFiles/'+custID);
                }
                else
                {
                    alert('There Was A Problem Updating File.\nPlease Contact System Administrator');
                }
            });
        }
    });
});

//  Open the delete file confirmation
$(document).on('click', '.delete-file', function()
{
    var fileID = $(this).data('fileid');
    $('#modal-header').html('Delete File');
    $('#modal-body').load('/home/yesorno', function()
    {
        $('.select-yes').addClass('delete-file');
        $('.select-yes').attr('data-fileid', fileID);
    });
});

//  Confirm delete existing contact
$(document).on('click', '.select-yes.delete-file', function()
{
    var fileID = $(this).data('fileid');
    $.get('/customer/deleteFileSubmit/'+fileID, function(data)
    {
        $('#edit-modal').modal('hide');
        if(data == 'success')
        {
            $('#customer-files-table').children('tbody').load('/customer/loadFiles/'+custID);
        }
        else
        {
            alert('There was a problem deleting the file');
        }
    });
});
