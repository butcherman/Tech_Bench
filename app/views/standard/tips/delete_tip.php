<div class="page-header">
    <h1 class="text-center">Delete Tech Tip</h1>
</div>
 <div id="yes-or-no-wrapper">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <h3 class="text-center">Please Confirm</h3>
            <h4 class="text-center">A Tech Tip cannot be recovered once it has been deleted</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2 col-sm-offset-4">
            <button class="btn btn-default btn-block select-yes">Yes</button>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-default btn-block select-no">No</button>
        </div>
    </div>
</div>

<script>
    $('.select-no').on('click', function()
    {
        parent.history.back();
        return false;
    });
    $('.select-yes').on('click', function()
    {
        $.get('/tips/deleteTipSubmit/<?= $data['tipID']; ?>', function(data)
        {
            if(data === 'success')
            {
                $('#yes-or-no-wrapper').html('<h3 class="text-center">Tip Deleted</h3>');
            }
            else
            {
                alert(data);
                $.post('/err/ajaxFail', {msg: data});
            }
        });
    });
</script>
