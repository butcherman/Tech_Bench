<div class="page-header">
    <h1 class="text-center"><?= $data['header']; ?></h1>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
                <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Created By</th>
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
