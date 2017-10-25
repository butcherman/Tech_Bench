<div class="page-header">
    <h1 class="text-center">View Log - <?= $data['logName'] ?></h1>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="jumbotron">
            <pre id="log-view"></pre>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h4 class="text-center">Auto Update</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2 text-center">
        <input type="checkbox" id="auto-update" name="auto-update" data-toggle="toggle" />
    </div>
</div>

<script>
    function loadLog()
    {
        $('#log-view').load('/maintenance/load-log/<?= $data['logName'] ?>');
    }
    
    var timer = '';
    
    $('#auto-update').on('change', function()
    {
        if($(this).is(':checked'))
        {
            timer = setInterval(loadLog, 1000);
        }
        else
        {
            clearInterval(timer);
        }
    });
    
    loadLog();
</script>
