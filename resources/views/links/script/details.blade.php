<script>
$(document).ready(function()
{
    $('#edit-link-details').on('click', function()
    {
        var url = '{{route('links.details.edit', ['id' => $data->link_id])}}';
        $('#edit-modal').find('.modal-title').text('Edit Link Info');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-file-link-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res)
                {
                    window.location.href = '{{route('links.info', ['id' => $data->link_id, 'name' => $data->link_name])}}';
                });
            });
        });
    });
    
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
});
    
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
            window.location.href = '{{route('links.index')}}';
        },
        error: function(res)
        {
            console.log(res);
            alert('Unable to Process Your Request');
        }
    });
}
</script>
