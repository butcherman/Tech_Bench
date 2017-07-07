//  Load the TinyMCE WYSIWYG Editor for all text area boxes
//tinymce.init(
//{ 
//    selector:'textarea',
//    height: '500'
//});
//  Load tooltips
$('body').tooltip({
    selector: '[data-tooltip="tooltip"]', 
    trigger: 'hover'
});
//  Load the System information from the database
$('#customer-system-information').load('/customer/loadSystems/'+custID);
//  Load the Contact information from the database
$('#contact-information').children('tbody').load('/customer/loadContacts/'+custID);




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
    $('#modal-header').html('Delete Contact');
    $('#modal-body').load('/home/yesorno');
    $('#yes-or-no-wrapper').addClass('delete-contact-wrapper');
});