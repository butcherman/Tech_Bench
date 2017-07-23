<div class="page-header">
    <h1 class="text-center">Create New User</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 id="settings-changed" class="text-center text-hide">User Successfully Added</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form id="new-user-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter A Unique Username" required />
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Enter the Users First Name" required />
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Enter the Users Last Name" required />
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Enter the Users Email Address" required />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter A  Password For the User" required />
            </div>
            <div class="form-group">
                <label for="confPass">Confirm Password:</label>
                <input type="password" id="confPass" name="confPass" class="form-control" placeholder="Re-Enter the New Password" required />
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default form-control" id="submit" value="Submit New User" />
            </div>
        </form>
    </div>
</div>

<script>
    $('#new-user-form').validate(
    {
        rules:
        {
            username: {
                remote: {
                    url: "/admin/checkForUser",
                    type: "post",
                    data: {
                        username: function() {
                            return $('#username').val();
                        }
                    }
                }
            },
            password: {
                minlength: 3
            },
            confPass: {
                minlength: 3,
                equalTo: "#password"
            }
        },
        messages:
        {
            username: "This Username is Already Taken",
            password: "Password must be a minimum of 3 characters long",
            confPass: "Please Re-Enter The Same Password Again"
        },
        submitHandler: function()
        {
            $.post('/admin/submitNewUser', $('#new-user-form').serialize(), function(data)
            {
                if(data === 'success')
                {
                    $('#settings-changed').removeClass('text-hide');
                    $('#username').val('');
                    $('#firstName').val('');
                    $('#lastName').val('');
                    $('#email').val('');
                    $('#newpass').val('');
                    $('#confpass').val('');
                }
                else
                {
                    alert(data);
                    alert('There was a problem adding the new user.');
                }
            });
        }
    });
</script>