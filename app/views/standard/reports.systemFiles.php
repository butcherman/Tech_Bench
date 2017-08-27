<div class="page-header">
    <h1 class="text-center">System Files Report</h1>
</div>

<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>Number of Files In System</th>
                    <th>Size of Disk</th>
                    <th>Free Space On Disk</th>
                    <th>Percentage of Disk Used</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $data['numFiles'] ?></td>
                    <td><?= $data['totalSpace'] ?></td>
                    <td><?= $data['freeSpace'] ?></td>
                    <td><?= $data['percent'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>