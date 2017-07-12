<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h1 class="text-center">Email Information</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="jumbotron">
            <form id="step3">
                <div class="form-group">
                    <label for="emHost">Email Host:</label>
                    <input type="text" name="emHost" id="emHost" class="form-control" placeholder="SMTP Host" required />
                </div>
                <div class="form-group">
                    <label for="emPort">Email Port:</label>
                    <input type="text" name="emPort" id="emPort" class="form-control" placeholder="SMTP Port" required />
                </div>
                <div class="form-group">
                    <label for="emUser">Email Username:</label>
                    <input type="text" name="emUser" id="emUser" class="form-control" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <label for="emAddr">Email Address:</label>
                    <input type="email" name="emAddr" id="emAddr" class="form-control" placeholder="Return Email Address" required />
                </div>
                <div class="form-group">
                    <label for="emPass">Email Password:</label>
                    <input type="password" name="emPass" id="emPass" class="form-control" placeholder="Email Password" required />
                </div>
                <div class="form-group">
                    <button class="btn btn-info btn-block" id="test-email">Test Connection</button>
                </div>
                <div class="form-group">
                    <input type="submit" id="step-3-submit" class="form-control btn btn-default" value="Continue to File Setup" />
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#test-email').click(function(e)
    {
        e.preventDefault();
        
        $('#test-email').html('<img src="/img/loader.gif" alt="loading" />');
        $.post('/setup/testEmail', {host: $('#emHost').val(), port: $('#emPort').val(), user: $('#emUser').val(), addr: $('#emAddr').val(), pass: $('#emPass').val()}, function(data)
        {
            var result =$.parseJSON(data);
            
            if(result == 'success')
            {
                $('#test-email').removeClass('btn-info');
                $('#test-email').addClass('btn-success');
                $('#test-email').html('Test Successful');
            }
            else
            {
                if($('#test-email').hasClass('btn-success'))
                {
                    $('#test-email').removeClass('btn-success');
                    $('#test-email').addClass('btn-info');
                }
                $('#test-email').html('Test Connection');
                alert(result);
            }
        });
    });
    
    $('#step3').validate(
    {
        validClass: "valid-input",
        errorClass: "invalid-input",
        submitHandler: function()
        {
            $.post('/setup/submit', $('#step3').serialize(), function(data)
            {
                if(data == 'success')
                {
                    window.location.replace('/setup/step-4');
                }
            });
        }
    });
</script>