<div class="page-header">
    <h1 class="text-center">Email Settings</h1>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 alert-success hidden">
        <h3 class="text-center">Email Settings Updated Succeessfully</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <form id="email-settings-form">
            <div class="form-group">
                <label for="emHost">Email Host:</label>
                <input type="text" name="emHost" id="emHost" class="form-control" placeholder="SMTP Host" value="<?= Config::getSetting('email_host') ?>" required />
            </div>
            <div class="form-group">
                <label for="emPort">Email Port:</label>
                <input type="text" name="emPort" id="emPort" class="form-control" placeholder="SMTP Port" value="<?= Config::getSetting('email_port') ?>" required />
            </div>
            <div class="form-group">
                <label for="emUser">Email Username:</label>
                <input type="text" name="emUser" id="emUser" class="form-control" placeholder="Username" value="<?= Config::getSetting('email_user') ?>" required />
            </div>
            <div class="form-group">
                <label for="emAddr">Email Address:</label>
                <input type="email" name="emAddr" id="emAddr" class="form-control" placeholder="Return Email Address" value="<?= Config::getSetting('email_from') ?>" required />
            </div>
            <div class="form-group">
                <label for="emPass">Email Password:</label>
                <input type="password" name="emPass" id="emPass" class="form-control" placeholder="Email Password" value="<?= Config::getSetting('email_pass') ?>" required />
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" id="test-email">Test Connection</button>
            </div>
            <div class="form-group">
                <input type="submit" id="step-3-submit" class="form-control btn btn-default" value="Update Email Settings" />
            </div>
        </form>
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
                $.post('/err/ajaxFail', {msg: result});
            }
        });
    });
    
    $('#email-settings-form').validate(
    {
        submitHandler: function()
        {
            $.post('/siteadministration/emailSettingsSubmit', $('#email-settings-form').serialize(), function(data)
            {
                if(data == 'success')
                {
                    $('.alert-success').removeClass('hidden');
                }
                else
                {
                    alert('There was a problem submitting your request');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>
