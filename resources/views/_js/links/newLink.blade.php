<script>
$(document).ready(function()
{
    //  Initialize Drag and Drop
    multiFileDrop($('#new-file-link-form'));
});
    
//  Search for customer when the "search" input looses focus
$('#customer-tag').on('blur', function(e)
{
    if($(this).val() != '')
    {
        searchID($(this).val());
    }
});
    
//  Search for custome rwhen the search button is pressed
$('#search-for-customer-button').on('click', function(e)
{
    e.preventDefault();
    searchID($('#customer-tag').val());
});
    
//  Populate the value of the customer input
$('#edit-modal').on('click', '.customer-selection', function(e)
{
    e.preventDefault();
    $('#customer-tag').val($(this).data('val')+' - '+$(this).data('name'));
    $('#edit-modal').modal('hide');
});

//  Function to search for a customer ID
function searchID(id)
{
    $.post('{{route('customer.searchID')}}', { '_token': '{{csrf_token()}}',  'name': id }, function(res)
    {
        $('#edit-modal').modal('show');
        $('#edit-modal').find('.modal-title').text('Select Customer');
        $('#edit-modal').find('.modal-body').html(res);
    });
}
    
//  Process the form after the submit button has been pressed
function uploadComplete(res)
{
    if($.isNumeric(res))
    {
        url = '{{ route('links.info', ['id' => ':id', 'subj' => ':sub']) }}';
        url = url.replace(':id', res);
        url = url.replace(':sub', $('#name').val());
        window.location.replace(url);
    }
    else
    {
        uploadFailed(res);
    }
}
    
//  Throw error if the upload failed
function uploadFailed(res)
{
    var msg = 'There was a problem adding the Tech Tip.\nPlease contact the system administrator';
    alert(msg+res);
}
</script>
