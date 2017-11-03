<div class="page-header">
    <h1 class="text-center">Delete An Existing System</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-success text-center hidden" id="alert-notification"></div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <div class="alert alert-warning text-center">
            <h3><strong>Important Note:</strong> A system can only be deleted if it is does not have any system files loaded to it and is not being used by a customer.</h3>
            <p>Make sure all files are deleted and no customers are assigned this system before trying to delete it.</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="form-group">
            <label for="category">Select System Category:</label>
            <select name="category" id="category" class="form-control" required>
                <option></option>
                <?= $data['categories']; ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <ul class="list-group" id="system-list"></ul>
    </div>
</div>
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="text-center" id="modal-header">Confirm System Delete</h4>
            </div>
            <div class="modal-body" id="modal-body">
                 <div id="yes-or-no-wrapper">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <h3 class="text-center">Please Confirm</h3>
                            <h4 class="text-center">This Action Cannot Be Undone</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-sm-offset-4">
                            <button class="btn btn-default select-yes">Yes</button>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-default select-no" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#category').on('change', function()
    {
        $('#system-list').load('/site-administration/load-system-types/'+$(this).val());
    });
    $(document).on('click', '.confirm-delete', function()
    {
        var sysType = $(this).data('value');
        $('.select-yes').on('click', function()
        {
            $.get('/site-administration/confirm-delete-system/'+sysType, function(data)
            {
                if(data === 'success')
                {
                    $('#alert-notification').removeClass('hidden');
                    $('#alert-notification').text('Successfully Deleted System.  Please reload page to refresh system list.');
                    $('#edit-modal').modal('hide');
                }
                else
                {
                    alert(data);
//                    alert('Unable to delete System.\nPlease confirm system is not assigned to any customers or has any files attached to it.')
                }
            });
        });
    });
</script>
