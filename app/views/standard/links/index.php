<div class="page-header">
    <h1 class="text-center">File Links</h1>
</div>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-md-4 col-md-offset-4">
        <a href="/links/create" class="btn btn-default btn-block">Create New File Link</a>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-10 col-md-offset-1">
                <div class="table-responsive">
            <table class="table table-striped" id="link-table">
                <thead>
                    <tr>
                        <th>Link Name</th>
                        <th class="hidden-xs">Expire Date</th>
                        <th># of Files</th>
                        <th class="hidden-xs">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $data['linkList']; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <p>
            <span class="bg-warning">Links in Yellow</span> are expired and should be deleted
        </p>
        <p>
            <span class="bg-danger">Links in Red</span> are more than 30 days expired and are in danger of being deleted on the next maintenance cycle.
        </p>
    </div>
</div>
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="text-center" id="modal-header">Delete Link</h4>
            </div>
            <div class="modal-body" id="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="/source/lib/filesize/filesize.min.js"></script>
<script src="/source/js/functions.fileLinks.js"></script>
<script src="/source/lib/tablesorter/jquery.tablesorter.combined.min.js"></script>
<script src="/source/lib/tablesorter/jquery.tablesorter.pager.min.js"></script>
<script>
$('#link-table').tablesorter(
{
    theme : "bootstrap",
    headerTemplate : '{content} {icon}',
    widgets : [ "uitheme", "zebra" ],
    widgetOptions : {
        zebra : ["even", "odd"],
    }
});
</script>
