$('body').tooltip(
{
    selector: '[data-tooltip="tooldip"]',
    trigger: 'hover'
})

$('#user-notifications').children('tbody').load('/dashboard/loadNotifications');

$(document).on('click', '.mark-notification', function()
{
    $(this).closest('tr').find('strong').contents().unwrap();
    $.get('/dashboard/markNotification/'+$(this).data('id'));
    $("#nav-user-notifications").text( Number($("#nav-user-notifications").text()) -1 );
});

$(document).on('click', '.notification-link', function(e)
{
    $(this).closest('tr').find('strong').contents().unwrap();
    $.get('/dashboard/markNotification/'+$(this).data('id'));
    $("#nav-user-notifications").text( Number($("#nav-user-notifications").text()) -1 );
});

$(document).on('click', '.delete-notification', function()
{
    if($(this).closest('tr').find('strong').length > 0)
    {
        $("#nav-user-notifications").text( Number($("#nav-user-notifications").text()) -1 );
    }

    $(this).closest('tr').remove();
    $.get('/dashboard/deleteNotification/'+$(this).data('id'));
});
