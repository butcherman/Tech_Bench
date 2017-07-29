<div class="page-header">
    <h1 class="text-center">File Links</h1>
</div>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-md-4 col-md-offset-4">
        <a href="/links/create" class="btn btn-default btn-block">Create New File Link</a>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
                <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Link Name</th>
                        <th class="hidden-xs">Expire Date</th>
                        <th># of Files</th>
                        <th class="hidden-xs">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php // echo $data['linkList']; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <p>
            <span class="bg-danger">Links in Red</span> are more than 30 days expired and are in danger of being deleted on the next maintenance cycle.
        </p>
        <p>
            <span class="bg-warning">Links in Yellow</span> are expired and should be deleted
        </p>
    </div>
</div>