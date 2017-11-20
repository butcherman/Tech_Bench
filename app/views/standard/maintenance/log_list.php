<div class="page-header">
    <h1 class="text-center">View System Logs</h1>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="alert-success hidden"><h3 class="text-center">Logs Archived Successfully</h3></div>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h3 class="text-center">Choose A Log To View</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <ul class="list-group" id="logs-list">
            <?= $data['logList'] ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <a href="/download/all-log-files" class="btn btn-default btn-block">Download All Logs</a>
        <a href="/maintenance/archiveLogs"  id="archive-logs-link" class="btn btn-default btn-block">Archive Logs</a>
    </div>
</div>

<script>
    $('#archive-logs-link').on('click', function(e)
    {
        e.preventDefault();
        $.get('/maintenance/archiveLogs');
        $('.alert-success').removeClass('hidden');
        $('#logs-list').html('');
    });
</script>
