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
            <h3 class="text-center">Running Backup</h3>
            <img src="/source/img/loader.gif" alt="Running Backup..." class="loadingModal" />
        </div>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-6 col-md-offset-3">
        <h3 class="text-center">Backup List</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Backup Name</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="backup-list"></tbody>
        </table>
    </div>
</div>

<script>
    loadBackups();
    
    $('#start-backup').on('click', function(e)
    {
        e.preventDefault();
        $('#backup-status-wraper').show();
        $.get('/maintenance/run-backup', function(e)
        {

            
            if(e === 'success')
            {
                setTimeout(function()
                {
                    $('#backup-status-wraper').hide();
                    loadBackups();
                }, 5000);
                
            }
            else
            {
                $('#backup-status-wraper').hide();
                alert('Backing up the system.  A log has been generated.');
                $.post('/err/ajaxFail', {msg: e});
            }
        });
    });
    
    function loadBackups()
    {
        $('#backup-list').load('/maintenance/load-backups');
    }
    
    $(document).on('change', '.backup-actions', function()
    {
        var fileName = $(this).parent().data('value');
        if($(this).val() === 'download')
        {
            window.location.href = '/download/backup/'+fileName;
        }
        else if ($(this).val() === 'delete')
        {
            $.get('/maintenance/delete-backup/'+fileName, function(e)
            {
                if(e === 'success')
                {
                    loadBackups();
                }
                else
                {
                    alert(e);
                }
            });
        }
    });
</script>
