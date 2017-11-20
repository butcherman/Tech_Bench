<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">New System Alert</h1>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="alert-success hidden" id="success-message">
                <h3 class="text-center">Alert Added Successfully</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form id="systemAlert">
                <div class="form-group">
                    <label for="message">Notification Message:</label>
                    <input type="text" name="message" id="message" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="expire">Expire Date:</label>
                    <input type="date" name="expire" id="expire" class="form-control" />
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
                Input a message above to see alert demo
            </div>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-8 col-md-offset-2 text-center">
            <p>A new system alert will show on the Dashboard of all users until the experation date.</p>
            <p>Basic HTML is allowed in the alert message and can include links and special formatting.</p>
        </div>
    </div>
</div>

<script>
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
    $('#systemAlert').validate(
    {
        rules: 
        {
            message: "required",
            expire: "required"
        },
        submitHandler: function()
        {
            $.post('/admin/submitSystemAlert', $('#systemAlert').serialize(), function(data)
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
                    alert('There Was A Problem Adding Alert.\nPlease Contact System Administrator');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>