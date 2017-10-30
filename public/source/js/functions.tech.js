jQuery.validator.addMethod("notEqualTo", function(value, element, param) {
    return this.optional(element) || value != $(param).val();
}, "This has to be different...");
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than {0}');
$.validator.addMethod("loginRegex", function(value, element) {
    return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
}, "Username must contain only letters, numbers, or dashes.");
$.validator.addMethod("standardChar", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\-\s]+$/.test(value);
}, "Value cannot contain any special characters");

$(document).ready(function()
{
    //  Toggle menu display
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("toggled");
    });
    
    //  Toggle Navigation menu items
    $('.nav-toggle').prev().click(function(e)
    {
        e.preventDefault();
        $(this).next().toggle(300);
    });
});
