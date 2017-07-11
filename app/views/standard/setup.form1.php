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
            <form id="step1" action="/setup/step-2" method="post">
                <div class="form-group">
                    <label for="siteURL">Site URL:</label>
                    <input type="url" name="siteURL" id="siteURL" class="form-control" placeholder="Enter the website URL - example: http://weburl.com" required />
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