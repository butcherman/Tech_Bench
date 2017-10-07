<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">Account Settings</h1>
    </div>
    <div>
        <h3 class="text-hide text-center text-warning" id="settings-changed">Settings Updated</h3>
    </div>
    <form id="userSettings">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-sm-offset-1 col-form-label text-right">Username:</label>
            <div class="col-sm-6">
                <input type="text" name="username" id="username" class="form-control" value="<?= $data['username']; ?>" readonly />
            </div>
        </div>
        <div class="form-group row">
            <label for="firstName" class="col-sm-2 col-sm-offset-1 col-form-label text-right">First Name:</label>
            <div class="col-sm-6">
                <input type="text" name="firstName" id="firstName" class="form-control" value="<?= $data['first_name']; ?>" />
            </div>
        </div>
        <div class="form-group row">
            <label for="lastName" class="col-sm-2 col-sm-offset-1 col-form-label text-right">Last Name:</label>
            <div class="col-sm-6">
                <input type="text" name="lastName" id="lastName" class="form-control" value="<?= $data['last_name']; ?>" />
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-sm-offset-1 col-form-label text-right">Email Address:</label>
            <div class="col-sm-6">
                <input type="email" name="email" id="email" class="form-control" value="<?= $data['email']; ?>" />
            </div>
        </div>
        <div class="page-header">
            <h1 class="text-center">Notification Settings</h1>
        </div>
        <div class="checkbox row">
            <label for="emailTechTip" class="col-sm-5 col-sm-offset-3 col-form-label">
                <input type="checkbox" id="emailTechTip" name="emailTechTip" data-toggle="toggle" <?= $data['em_tech_tip']; ?>>
                Receive Email on New Tech Tip
            </label>
        </div>
        <div class="checkbox row">
            <label for="emailFileLink" class="col-sm-5 col-sm-offset-3 col-form-label">
                <input type="checkbox" id="emailFileLink" name="emailFileLink" data-toggle="toggle" <?= $data['em_file_link']; ?>>
                Receive Email On Upload to a File Link
            </label>
        </div>
        <div class="checkbox row">
            <label for="emailNotifications" class="col-sm-5 col-sm-offset-3 col-form-label">
                <input type="checkbox" id="emailNotifications" name="emailNotifications" data-toggle="toggle" <?= $data['em_sys_notification']; ?>>
                Receive Email on System Notifications
            </label>
        </div>
<!--
        <div class="checkbox row">
            <label for="deleteLinks" class="col-sm-5 col-sm-offset-3 col-form-label">
                <input type="checkbox" id="deleteLinks" name="deleteLinks" data-toggle="toggle" <?php // echo $data['auto_delete_link']; ?>>
                Automatically Delete Expired File Links
            </label>
        </div>
        <p class="text-center"><strong>Note:</strong> When Automatically Delete Expired File Links is enabled, links and files will be deleted 30 days after they expire.</p>
-->
        <div class="form-group row">
            <div class="col-md-4 col-md-offset-4">
                <input type="submit" id="updateUser" class="form-control btn btn-default" value="Update Settings" />
            </div>
        </div>
    </form>
    <div class="text-center">
        <h3><a href="/account/password">Change Password</a></h3>
    </div>
</div>

<script>
    $('#userSettings').validate(
    {
        rules:
        {
            firstName: "required",
            lastName: "required",
            email: {
                required: true,
                email: true
            }
        },
        messages:
        {
            firstName: "Please Enter Your First Name",
            lastName: "Please Enter Your Last Name",
            email: "Please Enter A Valid Email Address"
        },
        submitHandler: function()
        {
            $.post('/account/submitUserSettings', $('#userSettings').serialize(), function(data)
            {
                if(data === 'success')
                {
                    $('#settings-changed').removeClass('text-hide');
                    var name = $('#firstName').val()+' '+$('#lastName').val();
                    $('#nav-user-name').text(name);
                }
                else
                {
                    alert('There was a problem updating your settings.');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>
