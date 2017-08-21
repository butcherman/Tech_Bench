<div class="page-header">
    <h1 class="text-center">Dashboard</h1>
</div>
<div class="row">
    <div class="col-md-12 text-center" id="system-alerts">
        <?= $data['sysAlerts']; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1 text-center" id="tech-alerts">
        <?= $data['userAlerts']; ?>
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                Notifications:
            </div>
            <div class="panel-body" id="notifications-panel">
                <table class="table table-striped" id="user-notifications">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Notification</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center">No Notifications</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="page-header">
            <h3 class="text-center">Customer Bookmarks</h3>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?= $data['customerFavs']; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="page-header">
            <h3 class="text-center">Tech Tip Bookmarks</h3>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?= $data['techTipFavs']; ?>            
    </div>
</div>

<script src="/source/js/functions.dashboard.js"></script>