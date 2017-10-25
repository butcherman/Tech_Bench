<div class="page-header">
    <h1 class="text-center">Add New System</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-success text-center hidden" id="alert-notification"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form id="new-system-form">
            <fieldset>
                <legend class="text-center">Primary System Information</legend>
                <div class="form-group">
                    <label for="category">Select System Category:</label>
                    <select name="category" id="category" class="form-control" required>
                        <option></option>
                        <?= $data['categories']; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sysName">System Name:</label>
                    <input type="text" name="sysName" id="sysName" class="form-control" placeholder="Input System Name" required />
                </div>
            </fieldset>
            <fieldset id="customer-information">
                <legend class="text-center">Customer Information To Gather</legend>
                <div class="form-group">
                    <label for="col1">System Data:</label>
                    <input type="text" name="col1" id="col1" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="col2">System Data:</label>
                    <input type="text" name="col2" id="col2" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="col3">System Data:</label>
                    <input type="text" name="col3" id="col3" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="col4">System Data:</label>
                    <input type="text" name="col4" id="col4" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="col5">System Data:</label>
                    <input type="text" name="col5" id="col5" class="form-control" />
                </div>
            </fieldset>
            <div class="form-group">
                <a href="#" id="add-customer-information" class="pull-right">Add Row</a>
            </div>
            <div class="form-group">
                <input type="submit" value="Add System" class="btn btn-default btn-block" />
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h4 class="text-center">Instructions</h4>
        <p>
            Adding a new system will allow users to store information such as Documentation, and Software along with storing information for customers such as IP Addresses and Version information.
        </p>
        <p>
            Select the category that the system should be filed under and give the system a descriptive name (the manufacturer name is recommended).
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
    var numRows = 5;
    $('#add-customer-information').click(function()
    {
        $('#customer-information').append('<div class="form-group"><label for="col'+numRows+'">System Data:</label><input type="text" name="col'+numRows+'" id="col'+numRows+'" class="form-control" /></div>');
        numRows++;
    });
    $('#new-system-form').validate(
    {
        rules:
        {
            sysName: 
            {
                alphanumeric: true
            }
        },
        messages:
        {
            category: "Please Select A Category",
            sysName: "Please Enter A Descriptive Name. Special Characters Such as & and - Cannot be used"
        },
        submitHandler: function()
        {
            $.post('/siteAdministration/createSystemSubmit', $('#new-system-form').serialize(), function(data)
            {
                $('#alert-notification').removeClass('hidden');
                if(data === 'success')
                {
                    $('#alert-notification').html('System Successfully Created.');
                    $('#systemNavLinks').load('/site-administration/reloadSysLinks');
                    $('#sysName').val('');
                    $('#customer-information').html('<legend class="text-center">Customer Information To Gather</legend><div class="form-group"><label for="col1">System Data:</label><input type="text" name="col1" id="col1" class="form-control" /></div><div class="form-group"><label for="col2">System Data:</label><input type="text" name="col2" id="col2" class="form-control" /></div><div class="form-group"><label for="col3">System Data:</label><input type="text" name="col3" id="col3" class="form-control" /></div><div class="form-group"><label for="col4">System Data:</label><input type="text" name="col4" id="col4" class="form-control" /></div><div class="form-group"><label for="col5">System Data:</label><input type="text" name="col5" id="col5" class="form-control" /></div>');
                }
                else
                {
                    $('#alert-notification').removeClass('alert-success');
                    $('#alert-notification').addClass('alert-danger');
                    $('#alert-notification').html('There Was A Problem Creating the System.<br />View Logs For More Infomation.');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>