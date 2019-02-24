//  Send email to reset password form
$('#password-reset-form').on('submit', function()
{
    $(this).find(':submit').val('Please Wait...').attr('disabled', 'disabled');
});

//  Reset password form
$('#password-reset-confirm').on('submit', function()
{
    $(this).find(':submit').val('Please Wait...').attr('disabled', 'disabled');
});

//  User Login form
$('#user-login-form').on('submit', function()
{
    $(this).find(':submit').val('Logging In...').attr('disabled', 'disabled');
});
