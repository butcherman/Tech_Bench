<div class="page-header">
    <h1 class="text-center">System Backup</h1>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <a href="#" id="start-backup" class="btn btn-default btn-block">Run Backup Now</a>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-8 col-md-offset-2">
        <div class="jumbotron" id="backup-status-wraper">
            <pre id="backup-status"></pre>
        </div>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-6 col-md-offset-3">
        <h3 class="text-center">Backup List</h3>
        <table class="table">
            
        </table>
    </div>
</div>

<script>
    $('#start-backup').on('click', function(e)
    {
        e.preventDefault();
        $('#backup-status-wraper').show();
        $('#backup-status').load('/maintenance/run-backup');
    });
</script>
