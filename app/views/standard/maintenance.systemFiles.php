<div class="page-header">
    <h1 class="text-center">System Files Report</h1>
</div>

<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <table class="table text-center">
            <thead>
                <tr>
                    <th class="text-center">Number of Files In System</th>
                    <th class="text-center">Size of Disk</th>
                    <th class="text-center">Free Space On Disk</th>
                    <th class="text-center">Percentage of Disk Used</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $data['numFiles'] ?></td>
                    <td id="total-space"><?= $data['totalSpace'] ?></td>
                    <td id="free-space"><?= $data['freeSpace'] ?></td>
                    <td><?= $data['percent'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="page-header">
            <h2 class="text-center">Missing Files</h2>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <p class="text-center">
            Missing files are noted in the database and may have a link somewhere in the application, but do not exist in the file location noted.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" class="check-all-missing" /></th>
                    <th class="text-center">File Location</th>
                </tr>
            </thead>
            <tbody>
                <?= $data['missing'] ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-lg-offset-5 col-sm-4 col-sm-offset-4">
        <button class="btn btn-default btn-block delete-missing">Delete Checked Files</button>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="page-header">
            <h2 class="text-center">Unknown Files</h2>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <p class="text-center">
            Unknown files are not listed in the database, but do exist in the file folders.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" class="check-all-unknown" /></th>
                    <th class="text-center">File Location</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?= $data['unknown'] ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-lg-offset-5 col-sm-4 col-sm-offset-4">
        <button class="btn btn-default btn-block delete-unknown">Delete Checked Files</button>
    </div>
</div>

<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    $('#total-space').text(filesize('<?= $data['totalSpace'] ?>'));
    $('#free-space').text(filesize('<?= $data['freeSpace'] ?>'));
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]', 
        trigger: 'hover'
    });
    $('.check-all-missing').on('click', function()
    {
        $('.missing-item').prop('checked', $(this).prop('checked'));
    });
    $('.check-all-unknown').on('click', function()
    {
        $('.unknown-item').prop('checked', $(this).prop('checked'));
    });
    $('.delete-missing').on('click', function()
    {
        $('.missing-item:checkbox:checked').each(function()
        {            
            $.post('/maintenance/delete-missing', { fileName: $(this).data('filename') }, function(e)
            {
                $.post('/err/ajaxFail', {msg: e});
            });
            $(this).closest('tr').remove();
        });
    });
    $('.delete-unknown').on('click', function()
    {
        $('.unknown-item:checkbox:checked').each(function()
        {            
            $.post('/maintenance/delete-missing', { fileName: $(this).data('filename') }, function(e)
            {
                $.post('/err/ajaxFail', {msg: e});
            });
            $(this).closest('tr').remove();
        });
    });
</script>
