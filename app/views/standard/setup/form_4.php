<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h1 class="text-center">Additional Information</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="jumbotron">
            <div class="breadcrumb-outer-wrapper">
                <div class="breadcrumb-inner-wrapper">
                    <a href="#" class="breadcrumb-link active">Step 1</a>
                    <a href="#" class="breadcrumb-link active">Step 2</a>
                    <a href="#" class="breadcrumb-link active">Step 3</a>
                    <a href="#" class="breadcrumb-link active">Step 4</a>
                    <a href="#" class="breadcrumb-link">Step 5</a>
                </div>
            </div>
            <div class="clearfix pad-bottom"></div>
            <form id="step4">
                <div class="form-group">
                    <label for="customerKey">Encryption Key - PLEASE COPY THIS KEY TO A SECURE LOCATION</label>
                    <input type="text" id="customerKey" name="customerKey" class="form-control" value="<?= $data['encryptionKey']; ?>" required />
                </div>
                <div class="checkbox row">
                    <label for="customCustID" class="col-sm-12 col-lg-6 col-lg-offset-3 col-form-label">
                        <input type="checkbox" id="customCustID" name="customCustID" data-toggle="toggle" checked />
                        <strong>Allow Custom Customer ID Numbers</strong>
                    </label>
                </div>
                <div class="checkbox row">
                    <label for="fileLinks" class="col-sm-12 col-lg-6 col-lg-offset-3 col-form-label">
                        <input type="checkbox" id="fileLinks" name="fileLinks" data-toggle="toggle" checked />
                        <strong>Allow Access to File Links</strong>
                    </label>
                </div>
                <div class="checkbox row">
                    <label for="companyForms" class="col-sm-12 col-lg-6 col-lg-offset-3 col-form-label">
                        <input type="checkbox" id="companyForms" name="companyForms" data-toggle="toggle" checked />
                        <strong>Allow Access to Company Forms</strong>
                    </label>
                </div>
                <div class="checkbox row">
                    <label for="companyForms" class="col-sm-12 col-lg-6 col-lg-offset-3 col-form-label">
                        <input type="checkbox" id="myFiles" name="myFiles" data-toggle="toggle" checked />
                        <strong>Allow Access to My Files</strong>
                    </label>
                </div>
                <div class="form-group">
                    <label for="uploadRoot">Root File Location:</label>
                    <input type="text" id="uploadRoot" name="uploadRoot" class="form-control" value="<?= $data['fileLocal']; ?>" placeholder="Enter The Root Location All Files Should Be Stored" />
                </div>
                <div class="form-group">
                    <label for="maxSize">Max File Size:</label>
                    <span id="maxValue"></span>
                    <div>
                        <input type="text" id="maxUpload" name="maxUpload" 
                               data-slider-id="maxUpload" 
                               data-slider-min="100000" 
                               data-slider-max="<?= $data['maxFile']; ?>"
                               data-slider-step="1000" 
                               data-slider-value="<?= $data['maxFile']; ?>"
                               data-slider-tooltip="hide"/>
                    </div>
                </div>
                <input type="hidden" name="default" value="default/" />
                <input type="hidden" name="userPath" value="users/" />
                <input type="hidden" name="custPath" value="cust_files/" />
                <input type="hidden" name="sysPath" value="sys_files/" />
                <input type="hidden" name="tipPath" value="tech_tips/" />
                <input type="hidden" name="formPath" value="company_forms/" />
                <input type="hidden" name="uploadPath" value="uploads/" />
                <div class="form-group">
                    <input type="submit" id="step-1-submit" class="form-control btn btn-default" value="Finish Tech Bench Setup" />
                </div>
            </form>
            <div>
                <h4 class="text-center">Tips:</h4>
                <ul>
                    <li><strong>Encryption Key:</strong> All customer information is encrypted before being stored in the database.  This is the key used for encrypting and decrypting this data. <span style="color: red">Please make a copy of this key and paste to a secure location in the event the config file is damaged.</span></li>
                    <li><strong>Allow Custom Customer ID Numbers</strong> If this is turned on, when creating a new customer, the user will be allowed to enter a custom Customer ID number.  If not selected, the customer ID number will be auto generated.</li>
                     <li><strong>Allow Access to File Links</strong> This section will allow users to create a public link to share with customers to upload files that may be too big for email.  Each file link has an expiration date and a visitor cannot upload a file to this application without having a valid file link.</li>
                    <li><strong>Allow Access to Company Forms</strong> This section is for global files that should be shared throughout the company.  Examples are "Time Off Requests" or "Incident Reports."</li>
                    <li><strong>Allow Access to My Files:</strong> This section allows individual users to upload files that only they can access.  These files are specifically for the user that uploaded them and cannot be shared or accessed by other users.</li>
                    <li><strong>Root File Location:</strong> Enter the base location that all files will be stored.  Only change if you know that the required folder has write access.</li>
                    <li><strong>Max File Size:</strong> Select the maximum size that is allowed to be uploaded by a user.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<link href="/source/lib/bootstrap-slider/css/bootstrap-slider.min.css" type="text/css" rel="stylesheet">
<script src="/source/lib/bootstrap-slider/bootstrap-slider.min.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    $('#maxUpload').slider();
    $('#maxUpload').on('slide', function(e)
    {
        var file = e.value;
        $('#maxValue').text(filesize(file));
    });
    
    var file = <?= $data['maxFile']; ?>;
    $('#maxValue').text(filesize(file));
    
    $('#step4').validate(
    {
        submitHandler: function()
        {
            if(confirm('Did You Make A Copy of The Encryption Key?'))
            {
                $.post('/setup/submit', $('#step4').serialize(), function(data)
                {
                    if(data == 'success')
                    {
                        window.location.replace('/setup/finish');
                    }
                    else
                    {
                        $.post('/err/ajaxFail', {msg: data});
                    }
                });
            }
        }
    });
</script>
