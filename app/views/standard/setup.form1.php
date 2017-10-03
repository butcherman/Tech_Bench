<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h1 class="text-center">Basic Information</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="jumbotron">
            <div class="breadcrumb-outer-wrapper">
                <div class="breadcrumb-inner-wrapper">
                    <a href="#" class="breadcrumb-link active">Step 1</a>
                    <a href="#" class="breadcrumb-link">Step 2</a>
                    <a href="#" class="breadcrumb-link">Step 3</a>
                    <a href="#" class="breadcrumb-link">Step 4</a>
                    <a href="#" class="breadcrumb-link">Step 5</a>
                </div>
            </div>
            <div class="clearfix pad-bottom"></div>
            <form id="step1">
                <div class="form-group">
                    <label for="title">Company Name:</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Your Company Name" required />
                </div>
                <div class="form-group">
                    <label for="baseURL">Site URL:</label>
                    <input type="url" name="baseURL" id="baseURL" class="form-control" placeholder="Enter the website URL - example: http://weburl.com" required />
                </div>
                <div class="form-group">
                    <label for="siteUser">Global Administrator Username:</label>
                    <input type="text" name="siteUser" id="siteUser" class="form-control" placeholder="Enter an administrator username for the Global Administrator" required />
                </div>
                <div class="form-group">
                    <label for="siteEmail">Global Administrator Email:</label>
                    <input type="email" name="siteEmail" id="siteEmail" class="form-control" placeholder="Enter the Global Administrator Email Address" required />
                </div>
                <div class="form-group">
                    <label for="sitePass">Global Administrator Password:</label>
                    <input type="password" name="sitePass" id="sitePass" class="form-control" placeholder="Enter the Global Administrator password" required />
                </div>
                <div class="form-group">
                    <label for="confPass">Confirm Password:</label>
                    <input type="password" name="confPass" id="confPass" class="form-control" placeholder="Confirm the Global Administrator password" required />
                </div>
                <div class="form-group">
                    <input type="submit" id="step-1-submit" class="form-control btn btn-default" value="Continue to Database Setup" />
                </div>
            </form>
            <div>
                <h4 class="text-center">Tips:</h4>
                <ul>
                    <li><strong>Company Name:</strong> Used to identify your company any time it needs to be referenced in emails and links.</li>
                    <li><strong>Site URL:</strong> The full site URL is used when creating links for the Tech Bench.</li>
                    <li><strong>Global Administrator Name:</strong> The Global Administrator has full system access.  This account cannot be disabled or adjusted by any other user</li>
                    <li><strong>Global Administrator Password:</strong> Remember to use a strong and secure password for the Global Administrator.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $('#step1').validate(
    {
        validClass: "valid-input",
        errorClass: "invalid-input",
        rules:
        {
            sitePass: {
                minlength: 3
            },
            confPass: {
                minlength: 3,
                equalTo: "#sitePass"
            }
        },
        submitHandler: function()
        {
            $.post('/setup/submit', $('#step1').serialize(), function(data)
            {
                if(data == 'success')
                {
                    window.location.replace('/setup/step-2');
                }
            });
        }
    });
</script>