jQuery.validator.addMethod("notEqualTo", function(value, element, param) {
    return this.optional(element) || value != $(param).val();
}, "This has to be different...");
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than {0}');

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