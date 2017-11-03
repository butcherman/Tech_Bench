<div class="page-header text-center">
    <h1>New Customer</h1>
</div>
<div class="row">
    <div id="customer-form-wrapper" class="col-md-8 col-md-offset-2">
        <form id="new-customer-form">
<?php if(Config::getCore('customCustID')) { echo '
            <div class="form-group">
                <label for="custID">Customer Site ID: <span class="glyphicon glyphicon-question-sign" title="What is this?" data-toggle="popover"></span></label>
                <input type="text" name="custID" id="custID" class="form-control" />
            </div>
'; } ?>
            <div class="form-group">
                <label for="custName">Customer Name:</label>
                <input type="text" name="custName" id="custName" class="form-control" />
            </div>
            <div class="form-group">
                <label for="custDBA">DBA Name/AKA:</label>
                <input type="text" name="custDBA" id="custDBA" class="form-control" />
            </div>
            <div class="form-group">
                <label for="custAddr">Address:</label>
                <input type="text" name="custAddr" id="custAddr" class="form-control" />
            </div>
            <div class="form-group">
                <label for="custCity">City:</label>
                <input type="text" name="custCity" id="custCity" class="form-control" />
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <select name="state" id="state" class="form-control">
                    <option value="AL">AL</option>
                    <option value="AK">AK</option>
                    <option value="AZ">AZ</option>
                    <option value="AR">AR</option>
                    <option value="CA" selected>CA</option>
                    <option value="CO">CO</option>
                    <option value="CT">CT</option>
                    <option value="DE">DE</option>
                    <option value="FL">FL</option>
                    <option value="GA">GA</option>
                    <option value="HI">HI</option>
                    <option value="ID">ID</option>
                    <option value="IL">IL</option>
                    <option value="IN">IN</option>
                    <option value="IA">IA</option>
                    <option value="KS">KS</option>
                    <option value="KY">KY</option>
                    <option value="LA">LA</option>
                    <option value="ME">ME</option>
                    <option value="MD">MD</option>
                    <option value="MA">MA</option>
                    <option value="MI">MI</option>
                    <option value="MN">MS</option>
                    <option value="MS">MS</option>
                    <option value="MO">MO</option>
                    <option value="MT">MT</option>
                    <option value="NE">NE</option>
                    <option value="NV">NV</option>
                    <option value="NH">NH</option>
                    <option value="NJ">NJ</option>
                    <option value="NM">NM</option>
                    <option value="NY">NY</option>
                    <option value="NC">NC</option>
                    <option value="ND">ND</option>
                    <option value="OH">OH</option>
                    <option value="OK">OK</option>
                    <option value="OR">OR</option>
                    <option value="PA">PA</option>
                    <option value="RI">RI</option>
                    <option value="SC">SC</option>
                    <option value="SD">SD</option>
                    <option value="TN">TN</option>
                    <option value="TX">TX</option>
                    <option value="UT">UT</option>
                    <option value="VA">VA</option>
                    <option value="WA">WA</option>
                    <option value="WI">WI</option>
                    <option value="WY">WY</option>
                </select>
            </div>
            <div class="form-group">
                <label for="zipCode">Zip Code:</label>
                <input type="number" name="zipCode" id="zipCode" class="form-control" maxlength="5" minlength="5" />
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-default" value="Add Customer" />
            </div>
        </form>
    </div>
</div>  

<script>
    $('body').popover({
        selector: '[data-toggle="popover"]',
        html: true,
        trigger: 'hover',
        content: '<h5>Customer Site ID is a unique identifier for each customer.  This should match your billing and dispatch software customer ID for simplicity.</h5>'
    });
    
    $('#new-customer-form').validate(
    {
        rules:
        {
<?php if(Config::getCore('customCustID')) { echo '
            custID:
            {
                required: true,
                number: true,
                remote: {
                    url: "/customer/checkID",
                    type: "post",
                    data: {
                        custID: function()
                        {
                            return $("#custID").val();
                        }
                    }
                }
            },
'; } ?>
            custName: "required",
            custAddr: "required",
            custCity: "required",
            state: "required",
            zipCode: 
            {
                required: true,
                number: true
            }
        },
        submitHandler: function()
        {
            $.post('/customer/addCustomerSubmit', $('#new-customer-form').serialize(), function(data)
            {
                if(data == 'success')
                {
                    var html = '<h3 class="text-center">Customer Successfully Added</h3><p class="text-center"><a href="/customer/id/'+$('#custID').val()+'/'+$('#custName').val()+'">Click</a> to go to profile</p><p class="text-center">Or, <a href="/customer/add">Click</a> to add another</p>';
                    $('#customer-form-wrapper').html(html);
                }
                else if(data == 'duplicate')
                {
                    var html = '<h3 class="text-center">Customer Already Exists</h3><p class="text-center"><a href="/customer/id/'+$('#custID').val()+'">Click</a> to go to profile</p>';
                    $('#customer-form-wrapper').html(html);
                }
                else
                {
                    alert('There Was A Problem Adding Customer');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        }
    });
</script>