<div class="page-header">
    <h1 class="text-center">Change User Settings</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 id="settings-changed" class="text-center text-hide">User Settings Changed</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
         <form id="settings-form">
            <h3 class="text-center">Select User to Modify</h3>
            <div class="form-group">
                <select id="selectUser" name="selectUser" class="form-control">
                    <?= $data['optList']; ?>
                </select>
            </div>
            <div id="form-wrapper"></div>
        </form>
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
            $('#form-wrapper').load('/admin/changeSettingsLoad/'+$(this).val());
        }
        else
        {
            $('#form-wrapper').addClass('hidden');
        }
    });
    
    $(document).on('click', '#settings-form-submit', function(e)
    {
        $('#settings-form').validate(
        {
            submitHandler: function()
            {
                $.post('/admin/changeSettingsSubmit', $('#settings-form').serialize(), function(data)
                {
                    if(data === 'success')
                    {
                        $('#settings-changed').removeClass('text-hide');
                    }
                    else
                    {
                        alert(data);
                        $.post('/err/ajaxFail', {msg: data});
                    }
                });
            }
        });
    });
</script>