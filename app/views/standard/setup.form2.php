<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h1 class="text-center">Database Information</h1>
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
                    <a href="#" class="breadcrumb-link">Step 3</a>
                    <a href="#" class="breadcrumb-link">Step 4</a>
                    <a href="#" class="breadcrumb-link">Step 5</a>
                </div>
            </div>
            <div class="clearfix pad-bottom"></div>
            <form id="step2">
                <div class="form-group">
                    <label for="host">Database Server:</label>
                    <input type="text" name="host" id="host" class="form-control" value="localhost" placeholder="Name or IP Address of Database Server" required />
                </div>
                <div class="form-group">
                    <label for="dbName">Database Name:</label>
                    <input type="text" name="dbName" id="dbName" class="form-control" placeholder="Name of Database to be Used" required />
                </div>
                <div class="form-group">
                    <label for="dbUser">Database User:</label>
                    <input type="text" name="dbUser" id="dbUser" class="form-control" placeholder="User That Manages Database" required />
                </div>
                <div class="form-group">
                    <label for="dbPass">Database Password:</label>
                    <input type="password" name="dbPass" id="dbPass" class="form-control" placeholder="Password" />
                </div>
                <div class="form-group">
                    <button class="btn btn-info btn-block" id="test-database">Test Connection</button>
                </div>
                <div class="form-group">
                    <input type="submit" id="step-2-submit" class="form-control btn btn-default" value="Continue to Email Setup" />
                </div>
            </form>
            
            <div>
                <h4 class="text-center">Tips:</h4>
                <ul>
                    <li><strong>Database Server:</strong> Enter the FQDN or IP Address of the server that will host the MySQL Database.  In most cases, it will be &quot;localhost&quot;</li>
                    <li><strong>Database Name:</strong> Enter the name of the database to be used. If the database does not exist, it will be created. <strong>Important:</strong> If the database already exists, the databse must be empty, any tables that are currently in the database will be removed and overwritten.</li>
                    <li><strong>Database User:</strong> The Database User must have full write access to the Tech Bench database.  It is not recommended to use the &quot;root&quot; user as this could present a security risk.</li>
                    <li><strong>Test Connection:</strong> Be sure to test your connection before continuing to ensure that your credentials are correct.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $('#test-database').click(function(e)
    {
        e.preventDefault();
        $.post('/setup/testDatabase', {server: $('#host').val(), database: $('#dbName').val(), user: $('#dbUser').val(), password: $('#dbPass').val()}, function(data)
        {
            var result = $.parseJSON(data);
            
            if(result == 'success')
            {                
                $('#test-database').removeClass('btn-info');
                $('#test-database').addClass('btn-success');
                $('#test-database').html('Test Successful');
            }
            else
            {
                if($('#test-database').hasClass('btn-success'))
                {
                    $('#test-database').removeClass('btn-success');
                    $('#test-database').addClass('btn-info');
                }
                alert(result);
            }            
        });
    });
    
    $('#step2').validate(
    {
        validClass: "valid-input",
        errorClass: "invalid-input",
        submitHandler: function()
        {
            $.post('/setup/submit', $('#step2').serialize(), function(data)
            {
                if(data == 'success')
                {
                    window.location.replace('/setup/step-3');
                }
                else
                {
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>
