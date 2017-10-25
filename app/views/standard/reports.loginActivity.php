<div class="page-header">
    <h1 class="text-center">User Login Activity</h1>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <table class="table">
            <thead>
                <tr>
                    <td colspan="2"></td>
                    <th colspan="3" class="text-center">Number of Logins in the Last</th>
                </tr>
                <tr>
                    <th>User</th>
                    <th>Last Login Date</th>
                    <th>7 Days</th>
                    <th>30 Days</th>
                    <th>90 Days</th>
                </tr>
            </thead>
            <tbody>
                <?= $data['loginTable']; ?>
            </tbody>
        </table>
    </div>
</div>
