<script>
$('.notification').on('closed.bs.alert', function()
{
    var id = $(this).data('id');
    var url = '{{route('mark-notification', ['id' => ':id'])}}';
    url = url.replace(':id', id);
    $.get(url);
});
</script>
