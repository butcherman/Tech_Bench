<div class="page-header">
    <h1 class="text-center">User Statistics</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 id="settings-changed" class="text-center text-hide">User Password Changed</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 class="text-center">Select User to View</h3>
        <div class="form-group">
            <select id="selectUser" name="selectUser" class="form-control">
                <?= $data['optList']; ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div id="report-wrapper"></div>
    </div>
</div>

<link href="/source/lib/select2/css/select2.min.css" type="text/css" rel="stylesheet">
<script src="/source/lib/select2/js/select2.min.js"></script>
<script>
    $('#selectUser').select2({
        placeholder: "Select A User"
    });
    
    $('#selectUser').on('change', function()
    {
        var userID = $(this).val();
        window.location.replace('/reports/user-stats/'+userID);
    });
</script>