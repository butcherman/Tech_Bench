<div class="page-header">
    <h1 class="text-center">System File Types</h1>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="alert-success hidden"><h3 class="text-center">New File Type Added</h3></div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <h3 class="text-center">Current File Types</h3>
        <ul class="list-group">
            <?= $data['fileTypes'] ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-lg-offset-5">
        <a href="#edit-modal" class="btn btn-default btn-block" data-toggle="modal">Add New File Type</a>
    </div>
</div>
<div class="row pad-top">
    <div class="col-lg-4 col-lg-offset-4">
        <h4 class="text-center">Click on File Type To Modify</h4>
    </div>
</div>

<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="text-center" id="modal-header">New File Type</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <form id="new-file-type-form">
                    <div class="form-group">
                        <label for="typeName">Type Name:</label>
                        <input type="text" name="typeName" id="typeName" class="form-control" required />
                    </div>
                    <input type="submit" value="Add New File Type" class="form-control" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#new-file-type-form').validate(
    {
        submitHandler: function()
        {
            $.post('/site-administration/submit-new-file-type', $('#new-file-type-form').serialize(), function(e)
                  {
                if(e === 'success')
                {
                    $('.alert-success').removeClass('hidden');
                    $('#edit-modal').modal('hide');
                }
                else
                {
                    alert(e);
                }
            });
        }
    });
    $('.edit-type-link').on('click', function()
    {
        var value = $(this).data('value');
        $('#modeal-header').text('Modify File Type');
        $('#modal-body').load('/site-administration/edit-file-type-form/'+value, function()
        {
            $('#edit-file-type-form').validate(
            {
                submitHandler: function()
                {
                    $.post('/site-administration/submit-edit-file-type/'+value, $('#edit-file-type-form').serialize(), function(e)
                    {
                        if(e === 'success')
                        {
                            location.reload();
                        }
                        else
                        {
                            alert(e);
                        }
                    });
                }
            });
        });
            
    });
    $(document).on('click', '#delete-file-type', function()
    {
        $.post('/site-administration/delete-file-type/'+$(this).data('value'), function(e)
        {
            if(e === 'success')
            {
                location.reload();
            }
            else
            {
                alert('This file type cannot be deleted because it has files assigned to it.\nPlease delete all associated files before deleting this fiel type.')
            }
        });
    });
</script>
