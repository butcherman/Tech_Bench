<div class="page-header">
    <h1 class="text-center">Company Information</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div id="settings-updated" class="alert-success hidden"><h3 class="text-center">Settings Updated</h3></div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form id="company-information-form">
            <div class="form-group">
                <label for="title">Company Name:</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= Config::getCore('companyName') ?>" required />
            </div>
            <div class="form-group">
                <label for="siteURL">Tech Bench URL</label>
                <input type="url" name="siteURL" id="siteURL" class="form-control" value="<?= Config::getCore('baseURL') ?>" required />
            </div>
            <input type="submit" class="form-control" value="Update Company Information" />
        </form>
    </div>
</div>
<div class="row pad-top pad-bottom">
    <div class="col-md-6 col-md-offset-3">
        <ul>
            <li><strong>Company Name:</strong> The name of your company.  Used in email links and headers.</li>
            <li><strong>Tech Bench URL:</strong> The full URL of this application.  This is used in all links both emailed and within the application.  Misconfiguring this section could cause the application to not work correctly.</li>
        </ul>
    </div>
</div>
<div class="page-header">
    <h2 class="text-center">Company Logo</h2>
</div>
<div class="row">
    <div class="col-sm-4 col-sm-offset-4" id="show-company-logo">
        <img src="/source/img/<?= Config::getCore('logo'); ?>" alt="Company Logo" class="text-center" id="header-logo" />
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form id="new-company-logo">
            <div class="form-group">
                <label for="newLogo">Update Logo:</label>
                <input type="file" name="newLogo" id="newLogo" class="form-control" />
            </div>
            <div class="progress" id="forProgressBar">
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
                    <span id="progressStatus"></span>
                </div>
            </div>
            <input type="submit" value="Update Logo" class="form-control" />
        </form>
    </div>
</div>

<script src="/source/js/functions.files.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    $('#company-information-form').validate(
    {
        submitHandler: function()
        {
            $.post('/site-administration/submit-company-settings', $('#company-information-form').serialize(), function(data)
            {
                if(data === 'success')
                {
                    $('#settings-updated').removeClass('hidden');
                }
                else
                {
                    alert('There was a problem submitting your request');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
    $('#new-company-logo').validate(
    {
        rules:
        {
            filesize: maxFile,
            required: true
        },
        messages:
        {
            filesize: "File size must be less than "+filesize(maxFile),
            required: "Please Select A File"
        },
        submitHandler: function()
        {
            submitFile('/site-administration/submit-new-logo', 'new-company-logo');
        }
    });
    
    function uploadComplete(res)
    {
        if(res === 'success')
        {
            location.reload();
        }
        else
        {
            alert('There Was A Problem Processing Your Request');
            $.post('/err/ajaxFail', {msg: data});
        }
    }
</script>
