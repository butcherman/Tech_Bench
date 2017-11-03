<div>
    <h2><span id="customer-fav" class="glyphicon glyphicon-bookmark <?= $data['custFav']; ?>" title="Bookmark Customer" data-tooltip="tooltip"></span><span id="name-span"><?= $data['custName']; ?></span></h2>
    <h5 id="dba-span"><?= $data['dbaName']; ?></h5>
</div>
<div id="customer-address">
    <address>
        <a href="https://maps.google.com/?q=<?= $data['addrLink']; ?>" target="_blank" id="addr-span"><?= $data['address']; ?></a>
        <span data-toggle="modal" data-target="#edit-modal">
            <span id="customer-edit" class="glyphicon glyphicon-pencil pointer" title="Edit Customer Information" data-tooltip="tooltip"></span>
        </span>
    </address>
</div>
<div class="row">
     <div class="col-md-5 col-md-offset-1 col-sm-12">
        <h3>Systems:</h3>
        <div id="customer-system-information"></div>
         <div class="text-center">
            <a href="#edit-modal" id="add-system-link" class="btn btn-default" data-toggle="modal">Add System</a>
            <a href="#edit-modal" id="edit-system-link" class="btn btn-default" data-toggle="modal">Edit System</a>
         </div>
    </div>
    <div class="col-md-5">
        <h3>Contacts:</h3>
        <div class="table-responsive">
            <table class="table" id="contact-information">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">
                            <a href="#edit-modal" id="add-contact-btn" class="btn btn-default" data-toggle="modal">
                                <span class="glyphicon glyphicon-plus"></span> 
                                Add Contact
                            </a>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center">No Contacts Please Add One</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>   
</div>
<div class="row">
    <div class="col-md-12">
        <h3>Notes:</h3>
    </div>
</div>
<div id="notes-wrapper"></div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <a href="#edit-modal" id="new-note-link" class="btn btn-default btn-block" data-toggle="modal">Add Note</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h3>Files:</h3>
        <div class="table-responsive">
            <table class="table" id="customer-files-table">
                <thead>
                    <tr>
                        <th>File Name:</th>
                        <th>File Type:</th>
                        <th>Uploaded By</th>
                        <th>Uploaded On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <a href="#edit-modal" id="add-file-link" class="btn btn-default btn-block" data-toggle="modal" data-backdrop="static" data-keyboard="false">Add File</a>
    </div>
</div>
    <div class="row">
    <div class="col-md-12">
        <h3>Linked Sites:</h3>
    </div>
</div>
<div class="row dashboard" id="linked-sites-wrapper">
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <a href="#edit-modal" id="add-linked-site" class="btn btn-default btn-block" data-toggle="modal" data-backdrop="static" data-keyboard="false">Link to Parent Site</a>
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

<script>
    var custID = <?= $data['custID']; ?>;
</script>
<script src="/source/lib/tinymce/tinymce.min.js"></script>
<script src="/source/lib/tinymce/plugins/autolink/plugin.min.js"></script>
<script src="/source/js/functions.files.js"></script>
<script src="/source/js/functions.customers.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
