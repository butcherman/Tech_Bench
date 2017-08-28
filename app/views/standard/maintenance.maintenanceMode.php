<div class="page-header">
    <h1 class="text-center">Maintenance Mode</h1>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2 text-center">
        <label for="maintenanceMode"><h3>Enable Maintenance Mode</h3></label>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2 text-center">
        <input type="checkbox" id="maintenanceMode" name="maintenanceMode" data-toggle="toggle" <?= $data['maintMode'] ?> >
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-8 col-md-offset-2">
        <p>
            Maintenance Mode will only allow Site Administrators to log into the Tech Bench.  All other users and visitors will be redirected to the Maintenance page.
        </p>
        <p>
            It is recommend to put the system into Maintenance Mode before doing an update or any major system changes such as a backup.
        </p>
    </div>
</div>

<script>
    $('#maintenanceMode').on('change', function()
    {
        $.get('/maintenance/toggleMaintMode');
    });
</script>