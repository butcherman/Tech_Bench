<div class="page-header text-center">
    <h1>System Global Settings</h1>
</div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="alert-success hidden">
            <h3 class="text-center">Settings Updated - Please Reload Page To Refresh Navigation</h3>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form id="update-settings-form">
            <div class="checkbox row">
                <label for="fileLinks" class="col-sm-12 col-form-label">
                    <input type="checkbox" id="fileLinks" name="fileLinks" data-toggle="toggle" <?= $data['links'] ?> />
                    Allow Access to File Links
                </label>
            </div>
            <div class="checkbox row">
                <label for="companyForms" class="col-sm-12 col-form-label">
                    <input type="checkbox" id="companyForms" name="companyForms" data-toggle="toggle" <?= $data['forms'] ?> />
                    Allow Access to Company Forms
                </label>
            </div>
            <div class="checkbox row">
                <label for="companyForms" class="col-sm-12 col-form-label">
                    <input type="checkbox" id="myFiles" name="myFiles" data-toggle="toggle" <?= $data['files'] ?> />
                    Allow Access to My Files
                </label>
            </div>
            <input type="submit" class="form-control" value="Update Settings" />
        </form>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-8 col-md-offset-2">
        <ul>
            <li><strong>File Links:</strong> This section will allow users to create a public link to share with customers to upload files that may be too big for email.  Each file link has an expiration date and a visitor cannot upload a file to this application without having a valid file link.</li>
            <li><strong>Company Forms:</strong> This section is for global files that should be shared throughout the company.  Examples are "Time Off Requests" or "Incident Reports."</li>
            <li><strong>My Files:</strong> This section allows individual users to upload files that only they can access.  These files are specifically for the user that uploaded them and cannot be shared or accessed by other users.</li>
        </ul>
    </div>
</div>

<script>
    $('#update-settings-form').validate(
    {
        submitHandler: function()
        {
            $.post('/siteadministration/submitSettingsForm', $('#update-settings-form').serialize(), function(data)
            {
                if(data === 'success')
                {
                    $('.alert-success').removeClass('hidden');
                }
                else
                {
//                    alert('Sorry, there was a problem processing your request');
                    alert(data);
                }
            });
        }
    });
</script>
