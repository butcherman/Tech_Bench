<div class="page-header">
    <h1 class="text-center">System Upgrade</h1>
</div>
<div class="jumbotron">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h3 class="text-center">Application Version: <?= $data['appVersion']; ?></h3>
        </div>
    </div>
    <div class="row pad-bottom">
        <div class="col-md-4 col-md-offset-2">
            <h4 class="text-center">Current Database Version: <?= $data['actual']; ?></h4>
        </div>
        <div class="col-md-4">
            <h4 class="text-center">Expected Database Version: <?= $data['expected']; ?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h4 class="text-center">Instructions</h4>
            <p>
                You can download the latest version from <a href="https://github.com/butcherman/Tech_Bench" title="Tech Bench on Git Hub" target="_blank"></a>
            </p>
            <p>
                After downloading the app version, copy the "App" and "Public" folders into the application replacing the current folders.
            </p>
            <p>
                Once the files have been copied, reload this page to see if the database needs to be updated.  Click the button below to update the database to the proper version.
            </p>
        </div>
    </div>
    <div class="row" id="upgrade-wrapper">
        <div class="col-md-6 col-md-offset-3">
            <button class="btn btn-default btn-block" id="upgrade-database">Click To Start Database Upgrade Process</button>
        </div>
    </div>
</div>

<script>
    $('#upgrade-database').click(function()
    {
        $('#upgrade-wrapper').html('<h3 class="text-center">Upgrade In Progress</h3><img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />');
        $.get('/upgrade/database', function(data)
        {
            if(data === 'success')
            {
                $('#upgrade-wrapper').html('<h3 class="text-center">Upgrade Complete</h3><h4 class="text-center"><a href="/dashboard">Click</a> to return to application</h4>');
            }
            else
            {
                alert(data);
                $.post('/err/ajaxFail', {msg: data});
            }
        });
    });
</script>