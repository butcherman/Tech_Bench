<div class="page-header">
    <h1 class="text-center">Deactivate User</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 id="settings-changed" class="text-center"></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
         <form id="deactivate-form">
            <h3 class="text-center">Select User to Deactivate</h3>
            <div class="form-group">
                <select id="selectUser" name="selectUser" class="form-control">
                    <?= $data['optList']; ?>
                </select>
            </div>
            <div id="form-wrapper" class="hidden">
                <a href="#confirm-modal" class="btn btn-default form-control deactivate-button" id="open-confirm-modal" data-toggle="modal">Deactivate This User</a>
             </div>
        </form>
    </div>
</div>

<div class="modal fade" id="confirm-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="text-center" id="modal-header">Deactivate User</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <h3 class="text-center">Please Confirm</h3>
                <h4 class="text-center" id="deactivate-name"></h4>
                <div class="row">
                    <div class="col-sm-2 col-sm-offset-4"><button class="btn btn-default select-yes">Confirm</button></div>
                    <div class="col-sm-2"><button class="btn btn-default" data-dismiss="modal">Cancel</button></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<link href="/source/lib/select2/css/select2.min.css" type="text/css" rel="stylesheet">
<script src="/source/lib/select2/js/select2.min.js"></script>
<script>
    $('#selectUser').select2({
        placeholder: "Select A User"
    });
    
    $('#selectUser').on('change', function()
    {
        if($(this).val() != '')
        {
            $('#form-wrapper').removeClass('hidden');
        }
        else
        {
            $('#form-wrapper').addClass('hidden');
        }
    });
    
    $('#open-confirm-modal').click(function()
    {
        var userID = $('#selectUser').val();
        var username = $('#selectUser option:selected').html();
        
        $('#deactivate-name').text('Deactivate '+username);
        $('.select-yes').click(function()
        {
            $.get('/admin/deactivateUserSubmit/'+userID, function(data)
            {
                $('#confirm-modal').modal('hide');
                if(data === 'success')
                {
                    $('#settings-changed').text('User '+username+' Has Been Deleted');
                    $('#selectUser option:selected').remove();
                }
                else
                {
                    alert(data);
//                    alert('There was an issue deelting user');
                }
            });
        });
    });
</script>