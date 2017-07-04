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
    $('#model-header').html('Add System');
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