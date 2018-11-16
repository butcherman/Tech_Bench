<script>
$(document).ready(function()
{
    loadLinks();
    
    $('.check-all-links').on('change', function()
    {
        if($(this).is(':checked'))
        {
            $('.check-link').prop('checked', true);
        }
        else
        {
            $('.check-link').prop('checked', false);
        }
    });
    
    $('#delete-checked').on('click', function()
    {
        $('#edit-modal').find('.modal-title').text('Delete Checked Links');
        $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
        {
            $('.select-yes').on('click', function()
            {
                $('.check-link').each(function()
                {
                    
                    if($(this).is(':checked'))
                    {
                        deleteLink($(this).val());
                    }
                });
            });
        });
    });
    
    $('#file-links-table').on('click', '.edit-link', function()
    {
        var url = '{{route('links.details.edit', ['id' => ':id'])}}';
        url = url.replace(':id', $(this).data('id'));
        $('#edit-modal').find('.modal-title').text('Edit Link Info');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-file-link-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res)
                {
                    resetEditModal();
                    loadLinks();
                });
            });
        });
    });
    
    $('#file-links-table').on('click', '.remove-link', function()
    {
        var linkID = $(this).data('id');
        $('#edit-modal').find('.modal-title').text('Delete Link');
        $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
        {
            $('.select-yes').on('click', function()
            {
                deleteLink(linkID);
            });
        });
    });
});  
    
//  Function to load file links
function loadLinks()
{
    var url = '{{route('links.details.show', ['id' => $current_user->user_id])}}';
    $('#file-links-table').find('tbody').load(url, function()
    {
        $('[data-tooltip="tooltip"]').tooltip();
    });
}
    
//  function to delete a file link
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
            console.log(res);
            resetEditModal();
            loadLinks();
        },
        error: function(res)
        {
            console.log(res);
            alert('Unable to Process Your Request');
        }
    });
}
</script>
