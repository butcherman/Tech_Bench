<div class="page-header">
    <h1 class="text-center">Link Details</h1>
</div>
<div class="row">
    <div class="col-xl-6 col-xl-offset-3 col-md-8 col-md-offset-2">
        <dl class="dl-horizontal">
            <dt>Link Name:</dt>
            <dd id="link-name"><?= $data['linkName']; ?></dd>
            <dt>Expire Date:</dt>
            <dd id="link-expire"><?= $data['expire']; ?></dd>
            <dt>Allow User Uploads:</dt>
            <dd id="link-allow"><?= $data['allow']; ?></dd>
            <dt>Link:</dt>
            <dd><a href="<?= $data['link']; ?>" target="_blank"><?= $data['link']; ?></a></dd>
        </dl>
    </div>
</div>
<div class="row">
    <div class="col-md-2 col-md-offset-2 col-xs-12">
        <a href="#edit-modal" class="btn btn-default btn-block" id="add-file" data-toggle="modal">Add File</a>
    </div>
    <div class="col-md-2 col-xs-12">
        <a href="#edit-modal" class="btn btn-default btn-block" id="edit-link" data-toggle="modal">Edit Link Data</a>
    </div>
    <div class="col-md-2 col-xs-12">
        <a href="#edit-modal" class="btn btn-default btn-block" id="share-link" data-toggle="modal">Share Link</a>
    </div>
<!--
    <div class="col-md-2 col-xs-12">
        <button type="button" class="btn btn-default btn-block">Copy Link to Clipboard</button>
    </div>
    <div class="col-md-2 col-xs-12">
        <button type="button" class="btn btn-default btn-block">Email Link</button>
    </div>
-->
    <div class="col-md-2 col-xs-12">
        <a href="#edit-modal" class="btn btn-default btn-block" id="delete-link" data-toggle="modal">Delete Link</a>
    </div>
</div>
<div class="page-header">
    <h2 class="text-center">Files</h2>
</div>
<div class="row">
    <div class="col-lg-6 uploadFiles" id="uploadFiles-left">
        <h3 class="text-center">Uploaded By Me</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th class="hidden-xs">Date Added</th>
                        <th>Notes</th>
                        <th class="hidden-xs">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-uploaded-files"></tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6 uploadFiles" id="uploadFiles-right">
        <h3 class="text-center">Uploaded By Customer</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th class="hidden-xs">Date Added</th>
                        <th>Notes</th>
                        <th class="hidden-xs">Actions</th>
                    </tr>
                </thead>
                <tbody id="customer-uploaded-files"></tbody>
            </table>
        </div>
    </div>
</div>
<div class="page-header">
    <h2 class="text-center">Custom Note</h2>
</div>
<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <form id="customNote">
            <textarea name="custom-note" id="custom-note" class="form-control" placeholder="You can input a custom note that will be included with the file download link."></textarea>
            <input type="submit" class="btn btn-block btn-default" value="Update Note" />
        </form>
    </div>
</div>

<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="text-center" id="modal-header">Modal Dialog</h4>
            </div>
            <div class="modal-body" id="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="/source/js/functions.files.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script src="/source/js/functions.fileLinks.js"></script>
<script src="/source/lib/tinymce/tinymce.min.js"></script>
<script src="/source/lib/tinymce/plugins/placeholder/plugin.min.js"></script>
<script>
    var linkID = <?= $data['linkID']; ?>;
    userUploadedFiles();
    customerUploadedFiles();
    loadInstruction();
</script>
