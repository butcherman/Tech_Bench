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
            <form id="step2">
                <div class="form-group">
                    <label for="dbServer">Database Server:</label>
                    <input type="text" name="dbServer" id="dbServer" class="form-control" value="localhost" placeholder="Name or IP Address of Database Server" required />
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
        </div>
    </div>
</div>

<script>
    $('#test-database').click(function(e)
    {
        e.preventDefault();
        $.post('/setup/testDatabase', {server: $('#dbServer').val(), database: $('#dbName').val(), user: $('#dbUser').val(), password: $('#dbPass').val()}, function(data)
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
            });
        }
    });
</script>