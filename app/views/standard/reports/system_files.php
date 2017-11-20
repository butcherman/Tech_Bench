<div class="page-header">
    <h1 class="text-center">System Files Report for Categories</h1>
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
    <div class="col-lg-6 col-lg-offset-3">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Valid Files <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Valid Files are files that exist in both the database and the file folders.  These are properly working files."></span></th>
                    <th>Missing Files <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Missing Files are listed in the database and have a link to them, but the file does not exist in the file folders and needs to be located or the link needs to be removed by the System Administrator."></span></th>
                    <th>Unknown Files <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Unknown Files exist in the file folder, but not in the database.  These are files that are just taking up space on the server and should be removed by the System Administrator."></span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Customer Files</strong></td>
                    <td><?= $data['custValid'] ?></td>
                    <td><?= $data['custMissing'] ?></td>
                    <td><?= $data['custUnknown'] ?></td>
                </tr>
                <tr>
                    <td><strong>System Files</strong></td>
                    <td><?= $data['sysValid'] ?></td>
                    <td><?= $data['sysMissing'] ?></td>
                    <td><?= $data['sysUnknown'] ?></td>
                </tr>
                <tr>
                    <td><strong>Tech Tip Files</strong></td>
                    <td><?= $data['tipValid'] ?></td>
                    <td><?= $data['tipMissing'] ?></td>
                    <td><?= $data['tipUnknown'] ?></td>
                </tr>
                <tr>
                    <td><strong>User Files</strong></td>
                    <td><?= $data['usrValid'] ?></td>
                    <td><?= $data['usrMissing'] ?></td>
                    <td><?= $data['usrUnknown'] ?></td>
                </tr>
                <tr>
                    <td><strong>Company Files</strong></td>
                    <td><?= $data['compValid'] ?></td>
                    <td><?= $data['compMissing'] ?></td>
                    <td><?= $data['compUnknown'] ?></td>
                </tr>
            </tbody>
        </table>
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
</script>
