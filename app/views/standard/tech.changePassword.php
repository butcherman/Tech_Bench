<div class="page-header">
    <h1 class="text-center">Change Password</h1>
</div>
<div>
    <h3 class="text-hide text-center text-warning" id="settings-changed">Password Updated</h3>
</div>
<div>
    <h3 class="text-hide text-center text-warning" id="change-password">You Must Change Your Password to Continue</h3>
</div>
<form id="changePassword">
    <div class="form-group row">
        <label for="current" class="col-sm-2 col-sm-offset-1 col-form-label text-right">Current Password:</label>
        <div class="col-sm-6">
            <input type="password" name="current" id="current" class="form-control" />
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>
    </div>
    <div class="form-group row">
        <label for="newpass" class="col-sm-2 col-sm-offset-1 col-form-label text-right">New Password:</label>
        <div class="col-sm-6">
            <input type="password" name="newpass" id="newpass" class="form-control" />
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>
    </div>
    <div class="form-group row">
        <label for="confpass" class="col-sm-2 col-sm-offset-1 col-form-label text-right">Confirm Password:</label>
        <div class="col-sm-6">
            <input type="password" name="confpass" id="confpass" class="form-control" />
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xs-2 col-md-offset-5 col-xs-offset-3">
            <input type="submit" id="updatePadd" class="btn btn-default" value="Update Password" />
        </div>
    </div>
</form>

<script>
    $('#changePassword').validate(
    {
        rules:
        {
            current: {
                required: true,
                remote: {
                    url: "/account/checkPassword",
                    type: "post",
                    data: {
                        current: function() {
                            return $('#current').val();
                        }
                    }
                }
            },
            newpass: {
                required: true,
                minlength: 3,
                notEqualTo: "#current"
            },
            confpass: {
                required: true,
                minlength: 3,
                equalTo: "#newpass"
            }
        },
        messages:
        {
            current: {
                required: "Please enter your current password",
                remote: "Invalid current password"
            },
            newpass: {
                minlength: "Must be at least 3 characters",
                notEqualTo: "Cannot match your current password"
            },
            confpass: {
                notEqualTo: "Cannot match your current password",
                equalTo: "Passwords do not match"
            }
        },
//        success: function(element)
//        {
//            $(element).closest('.form-group').addClass('has-success has-feedback');
//            $(element).next('.form-control-feedback').addClass('glyphicon glyphicon-ok');
//        },
        submitHandler: function()
        {
            $.post('/_account/updatePassword', $('#changePassword').serialize(), function(data)
            {
                $('#settings-changed').removeClass('text-hide');
                $('#change-password').addClass('text-hide');
            });
        }
    });
</script>