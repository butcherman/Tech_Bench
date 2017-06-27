$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than {0}');

$('#login-form').validate(
{
    rules: 
    {
        username: "required",
        password: "required"
    },
    messages:
    {
        username: "Please Enter A Username",
        password: "Password Required"
    },
    submitHandler: function()
    {
        $.post('/home/submitLogin', $('#login-form').serialize(), function(data)
        {                
            if(data == '')
            {
                $('#form-error').show();
            }
            else
            {
                window.location.replace(data);
            }
        });
    }
});

$('#forgot-password-form').validate(
{
    submitHandler: function()
    {
        $('#form-error').show();
        $.post('/home/submitForgotPassword', $('#forgot-password-form').serialize(), function(data)
        {
            if(data === 'success')
            {
                $('#form-error').html('Email Sent to '+$('#email').val()+' with instructions for resetting password');
            }
            else
            {
                $('#form-error').html('There Was An Error Processing Your Request');
            }
        });
    }
});

$('#reset-password-form').validate(
{
    rules:
    {
        confPass: {
            equalTo: '#newPass'
        }
    },
    submitHandler: function()
    {
        $.post('/home/submitResetPassword', $('#reset-password-form').serialize(), function(data)
        {
            if(data ===  'success')
            {
                $('#form-error').html('Password Reset.  Please <a href="/">Login</a> To Continue.');
            }
            else
            {
                $('#form-error').html('There was an error processing your request');
                alert(data);
            }
            $('#form-error').show();
        });
    }
});
