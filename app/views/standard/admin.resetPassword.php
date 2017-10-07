<div class="page-header">
    <h1 class="text-center">Reset User Password</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 id="settings-changed" class="text-center text-hide">User Password Changed</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
         <form id="reset-password-form">
            <h3 class="text-center">Select User to Modify</h3>
            <div class="form-group">
                <select id="selectUser" name="selectUser" class="form-control">
                    <?= $data['optList']; ?>
                </select>
            </div>
            <div id="form-wrapper" class="hidden">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="confPass">Confirm Password</label>
                    <input type="password" id="confPass" name="confPass" class="form-control" required />
                </div>
                <div class="checkbox row">
                    <label for="forceChange" class="col-sm-10 col-sm-offset-1 col-form-label">
                        <input type="checkbox" id="forceChange" name="forceChange" data-toggle="toggle" checked>
                        Force Password Change On Next Login
                    </label>
                </div>
                <div class="form-group">
                    <input type="submit" class="form-control btn btn-default" value="Reset Password" />
                </div>
             </div>
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
            $('#form-wrapper').removeClass('hidden');
        }
        else
        {
            $('#form-wrapper').addClass('hidden');
        }
    });
    
    $('#reset-password-form').validate(
    {
        rules:
        {
            password: {
                minlength: 3
            },
            confPass: {
                minlength: 3,
                equalTo: "#password"
            }
        },
        submitHandler: function()
        {
            $.post('/admin/resetPasswordSubmit', $('#reset-password-form').serialize(), function(data)
            {
                if(data === 'success')
                {
                    $('#settings-changed').removeClass('text-hide');
                    $('#selectUSer').val('');
                    $('#password').val('');
                    $('#confPass').val('');
                }
                else
                {
                    alert('There Was A Problem Resetting The Password');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>