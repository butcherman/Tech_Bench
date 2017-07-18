<div class="page-header">
    <h1 class="text-center">Edit System</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-success text-center hidden" id="alert-notification"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form id="edit-system-form">
            <fieldset>
                <legend class="text-center">Primary System Information</legend>
                <div class="form-group">
                    <label for="category">Select System Category:</label>
                    <select name="category" id="category" class="form-control" required>
                        <option></option>
                        <?= $data['categories']; ?>
                    </select>
                </div>
                <div id="sys-name-selector" class="form-group hidden">
                    <label for="systems">Select System</label>
                    <select name="systems" id="systems" class="form-control" required>
                        <option></option>
                    </select>
                </div>
                <div id="sys-data-selector" class="hidden">
                    <div class="form-group">
                        <label for="sysName">System Name:</label>
                        <input type="text" name="sysName" id="sysName" class="form-control" placeholder="Input System Name" required />
                    </div>
                </div>
            </fieldset>
            <fieldset id="customer-information" class="customer-information hidden">
                <legend class="text-center">Customer Information To Gather</legend>
            </fieldset>
            <div class="form-group customer-information hidden">
                <a href="#" id="add-customer-information" class="pull-right">Add Row</a>
            </div>
            <div class="form-group customer-information hidden">
                <input type="submit" value="Update System" class="btn btn-default btn-block" />
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h4 class="text-center">Instructions</h4>
        <p>
            Editing an existing system will allow you to update the information for a system that has already been defined.
        </p>
        <p>
            Select the category that the system is filed under and give the system a descriptive name (the manufacturer name is recommended).
        </p>
        <p>
            For the Customer Information, enter the information that should be gathered for each customer that has one of these systems.  Suggested information is:
        </p>
        <ul>
            <li>System Version</li>
            <li>IP Address</li>
            <li>Login Username</li>
            <li>Login Password</li>
        </ul>
        <p>
            Add additional rows by clicking the "Add Row" link.
        </p>
    </div>
</div>

<script>
    $('#category').on('change', function()
    {
        $('#systems').load('/site-administration/loadSysNames/'+$(this).val());
        $('#sys-name-selector').removeClass('hidden');
    });
    $('#systems').on('change', function()
    {
        $('#customer-information').load('/site-administration/loadSysData/'+$(this).val());
        $('.customer-information').removeClass('hidden');
    });
    $('#add-customer-information').click(function()
    {
        $('#customer-information').append('<div class="form-group"><label for="col'+numRows+'">System Data:</label><input type="text" name="col'+numRows+'" id="col'+numRows+'" class="form-control" /></div>');
        numRows++;
    });
    $('#edit-system-form').validate(
    {
        submitHandler: function()
        {
            $.post('/siteAdministration/createSystemSubmit', $('#new-system-form').serialize(), function(data)
            {
                $('#alert-notification').removeClass('hidden');
                if(data === 'success')
                {
                    $('#alert-notification').html('System Successfully Modified.');
                }
                else
                {
                    alert(data);
                    $('#alert-notification').removeClass('alert-success');
                    $('#alert-notification').addClass('alert-danger');
                    $('#alert-notification').html('There Was A Problem Updating the System.<br />View Logs For More Infomation.');
                }
            });
        }
    });
</script>