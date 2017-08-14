<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">User Alert Message</h1>
    </div>
     <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="alert-success hidden" id="success-message">
                <h3 class="text-center">Alert Added Successfully</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form id="new-user-alert">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-5">
                            <label for="multiselect">Select User(s)</label>
                            <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                                <?= $data['optList']; ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <lebel for="multiselect_rightAll">&nbsp;</lebel>
                            <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                            <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                            <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                            <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                        </div>

                        <div class="col-xs-5">
                            <label for="multiselect_to">Selected User(s)</label>
                            <select name="to[]" id="multiselect_to" class="form-control" size="8" multiple="multiple"></select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="message">Alert Message:</label>
                    <input type="text" name="message" id="message" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="expire">Expire Date:</label>
                    <input type="date" name="expire" id="expire" class="form-control" />
                </div>
                <div class="checkbox row">
                    <label for="dismissable" class="col-sm-6 col-form-label">
                        <input type="checkbox" id="dismissable" name="dismissable" data-toggle="toggle" checked>
                        Allow User to Dismiss Alert
                    </label>
                </div>
                <div class="form-group">
                    <label for="level">Alert Level/Color</label>
                    <select name="level" id="level" class="form-control">
                        <option value="success">Success</option>
                        <option value="info">Information</option>
                        <option value="warning">Warning</option>
                        <option value="danger">Danger</option>
                    </select>
                </div>
                <input type="submit" value="Publish Alert" class="form-control" />
            </form>
        </div>
    </div>
     <div class="row top-buffer">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-success text-center" id="sample-alert">
                <a href="#" class="close" id="dismiss-icon">&times;</a>
                Input a message above to see alert demo
            </div>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-8 col-md-offset-2 text-center">
            <p>A new user alert will show on the Dashboard of the selected users until the experation date or they have dismissed it.</p>
            <p>By turning off the dismiss option, a notification will not have the ability to be manually dismissed.</p>
            <p>Basic HTML is allowed in the alert message and can include links and special formatting.</p>
        </div>
    </div>
</div>

<script src="/source/lib/multiselect-two-sides/multiselect.min.js"></script>
<script>
    $('#multiselect').multiselect();
    $('#message').on('keyup', function()
    {
        var text = $(this).val();
        $('#sample-alert').html(text);
    });
    $('#level').on('change', function()
    {
        var c = 'alert-'+$(this).val();
        $("#sample-alert").removeClass (function (index, css) 
        {
            return (css.match (/(^|\s)alert-\S+/g) || []).join(' ');
        });
        $('#sample-alert').addClass(c);
    });
    $('#dismissable').on('change', function()
    {
        $('#dismiss-icon').toggle();
    });
    $('#new-user-alert').validate(
    {
        rules: 
        {
            multiselect_to: "required",
            message: "required",
            expire: "required"
        },
        submitHandler: function()
        {
            $.post('/admin/userAlertSubmit', $('#new-user-alert').serialize(), function(data)
            {
                if(data == 'success')
                {
                    $('#success-message').removeClass('hidden');
                    $('#message').val('');
                    $('#expire').val('');
                    $('#sample-alert').html('Input a message above to see alert demo');
                    $("#sample-alert").removeClass (function (index, css) 
                    {
                        return (css.match (/(^|\s)alert-\S+/g) || []).join(' ');
                    });
                    $('#sample-alert').addClass('alert-success');
                }
                else
                {
                    alert(data);
      //              alert('There Was A Problem Adding Alert.\nPlease Contact System Administrator');
                }
            });
        }
    });
</script>