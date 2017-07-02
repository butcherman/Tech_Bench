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
 //   e.preventDefault();
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
                alert(data);
  //              window.location.replace(url);
            });
        }
    });
});