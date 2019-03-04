<script>
$(document).ready(function()
{
    loadFiles();
    
    //  Copy link details to clipboard
    var clip = new ClipboardJS('.copy-btn');
    clip.on('success', function()
    {
        $('.copy-btn').addClass('text-success');
    });
    
    //  Edit a link's details
    $('#edit-link-details').on('click', function()
    {
        $('#edit-modal').modal('show');
        var url = '{{route('links.details.edit', ['id' => $data->link_id])}}';
        $('#edit-modal').find('.modal-title').text('Edit Link Info');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-file-link-form').on('submit', function(e)
            {
                e.preventDefault();
                $(this).find(':submit').val('Updating...').attr('disabled', 'disabled');
                $.post($(this).attr('action'), $(this).serialize(), function(res)
                {
                    window.location.href = '{{route('links.info', ['id' => $data->link_id, 'name' => urlencode($data->link_name)])}}';
                });
            });
        });
    });
    
    //  Attach link to customer
    $('#link-to-cust-btn').on('click', function()
    {
        $('#edit-modal').find('.modal-title').text('Attach to Customer');
        $('#edit-modal').find('.modal-body').load('{{route('links.link-cust', ['id' => $data->link_id])}}', function()
        {
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
                $('#cust-list-append').html('');
            });
            
            //  Submit the add customer form
            $('#new-customer-link-form').submit(function(e)
            {
                e.preventDefault();
                var url = '{{route('links.updateCustomer', ['id' => $data->link_id])}}'
                $.ajax(
                {
                    url: url,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'customer_tag': $('#customer-tag').val()
                    },
                    success: function(res)
                    {
                        location.reload();
                    },
                    error: function(res)
                    {
                        console.log(res);
                        alert('Unable to Process Your Request');
                    }
                });
            });
        });
    });
    
    //  Delete the link
    $('#delete-link-btn').on('click', function()
    {
        $('#edit-modal').find('.modal-title').text('Delete Link');
        $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
        {
            $('.select-yes').on('click', function()
            {
                deleteLink('{{$data->link_id}}');
            });
        });
    });
    
    //  Delete a file attached to the link
    $(document).on('click', '.remove-file', function()
    {
        var fileID = $(this).data('id');
        $('#edit-modal').find('.modal-title').text('Delete File');
        $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
        {
            $('.select-yes').on('click', function()
            {
                deleteFile(fileID);
            });
        });
    });
    
    //  Delete multiple download files attached to the link
    $(document).on('click', '#delete-multiple-down', function(e)
    {
        //  Verify that there is a file checked first
        e.preventDefault();
        var checked = false;
        $('.checkbox-file-down').each(function()
        {
            if($(this).is(':checked'))
            {
                checked = true;
                return false;
            }
        });
        
        //  Delete the selected files
        if(checked)
        {
            $('#edit-modal').modal('show');
            $('#edit-modal').find('.modal-title').text('Delete File');
            $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
            {
                $('.select-yes').on('click', function()
                {
                    $('.checkbox-file-down').each(function()
                    {
                        if($(this).is(':checked'))
                        {
                            deleteFile($(this).val());
                        }
                    });
                });
            });
        }
    });
    
    //  Delete multiple upload files attached to the link
    $(document).on('click', '#delete-multiple-up', function(e)
    {
        //  Verify there is a file to delete first
        e.preventDefault();
        var checked = false;
        $('.checkbox-file-up').each(function()
        {
            if($(this).is(':checked'))
            {
                checked = true;
                return false;
            }
        });
        
        //  Delete the selected files
        if(checked)
        {
            $('#edit-modal').modal('show');
            $('#edit-modal').find('.modal-title').text('Delete File');
            $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
            {
                $('.select-yes').on('click', function()
                {
                    $('.checkbox-file-up').each(function()
                    {
                        if($(this).is(':checked'))
                        {
                            deleteFile($(this).val());
                        }
                    });
                });
            });
        }
    });
    
    //  Read a note attached to a file
    $(document).on('click', '.read-file-note', function()
    {
        var noteID = $(this).data('noteid');
        var url = '{{route('links.note', ['id' => ':id'])}}';
        url = url.replace(':id', noteID);
        $('#edit-modal').find('.modal-title').text('File Note');
        $('#edit-modal').find('.modal-body').load(url);
    });
    
    //  Add another file to the link
    $(document).on('click', '#add-link-file', function()
    {
        var url = '{{route('links.addFile', ['id' => $data->link_id])}}';
        $('#edit-modal').find('.modal-title').text('Add File');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            multiFileDrop($('#add-file-form'));
        });
    });
    
    //  Place file under customer files
    $(document).on('click', '.move-file', function()
    {
        var fileID = $(this).data('id');
        $('#edit-modal').find('.modal-title').text('Move File');
        $('#edit-modal').find('.modal-body').load('{{route('customer.getFileTypes')}}', function()
        {
            //  Fill hidden form values
            $('#file_id').val(fileID);
            $('#cust_id').val('{{$data->cust_id}}');
            
            //  Submit the move file form
            $('#move-file-form').submit(function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res)
                {
                    if(res === 'duplicate')
                    {
                        $('#edit-modal').find('.modal-body').html('<h3 class="text-center">Customer Already Has This File</h3>')
                    }
                    else if(res === 'success')
                    {
                        $('#edit-modal').modal('hide');
                        loadFiles();
                    }
                    else
                    {
                        alert('Sorry, but there was a problem processing your request');
                    }
                });
            });
        });
    });
    
    //  Edit instructions for gues to see when visiting the link
    $('#edit-link-note').on('click', function()
    {
        $('#edit-modal').modal('show');
        $('#edit-modal').find('.modal-dialog').addClass('modal-lg');
        $('#edit-modal').find('.modal-title').text('Edit Instructions');
        $('#edit-modal').find('.modal-body').load('{{route('links.instructionForm', ['id' => $data->link_id])}}', function()
        {
            tinymce.init(
            {
                selector: 'textarea',
                height: '400',
                plugins: 'autolink'
            });
            $('#instructions-form').submit(function(e)
            {
                e.preventDefault();
                $(this).find(':submit').val('Loading...').attr('disabled', 'disabled');
                $.post($(this).attr('action'), $(this).serialize(), function(res)
                {
                    if(res === 'success')
                    {
                        location.reload();
                    }
                    else
                    {
                        alert('There was an issue processing your request');
                    }
                });
            });
        });
    });
});
    
//  Function to search for a customer ID
function searchID(id)
{
    $.post('{{route('customer.searchID')}}', { '_token': '{{csrf_token()}}',  'name': id }, function(res)
    {
        $('#cust-list-append').html(res);
    });
}  

//  Function to load all files attached to the link
function loadFiles()
{
    $('#files-to-download').load('{{route('links.getFiles', ['type' => 'down', 'id' => $data->link_id])}}', function()
    {
        $('#files-uploaded').load('{{route('links.getFiles', ['type' => 'up', 'id' => $data->link_id])}}', function()
        {
            initTooltip();
        });
    });
    
}
    
//  function to delete a file attached to the link
function deleteFile(file)
{
    var url = '{{route('links.deleteFile', ['id' => ':id'])}}'
    url = url.replace(':id', file);
    $.ajax(
    {
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res)
        {
            resetEditModal();
            loadFiles();
        },
        error: function(res)
        {
            console.log(res);
            alert('Unable to Process Your Request');
        }
    });
}
    
//  function to delete a link
function deleteLink(linkID)
{
    var url = '{{route('links.details.destroy', ['id' => ':id'])}}'
    url = url.replace(':id', linkID);
    $.ajax(
    {
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res)
        {
            window.location.href = '{{route('links.index')}}';
        },
        error: function(res)
        {
            console.log(res);
            alert('Unable to Process Your Request');
        }
    });
}
    
function uploadComplete(res)
{
    resetEditModal();
    loadFiles();
}
function uploadFailed(res)
{
    var msg = 'There was a problem adding the Tech Tip.\nPlease contact the system administrator';
    console.log(res);
}
</script>
