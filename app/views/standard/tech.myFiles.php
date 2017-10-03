<div class="container-fluid">
    <div class="page-header text-center">
        <h1>My Files</h1>
    </div>
    <div class="row">
        <div class="col-xs-8 col-xs-offset-2 col-md-4 col-md-offset-4">
            <a href="#new-file" class="btn btn-default btn-block" data-toggle="modal">Add New File</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="row top-buffer" id="my-file-wrapper"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="new-file">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4>Add New File:</h4>
            </div>
            <div class="modal-body">
                <form id="new-file-form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Descriptive Name For File" autofocus required />
                    </div>
                    <div class="form-group">
                        <label for="file">File:</label>
                        <input type="file" name="file" id="file" class="form-control" required />
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" id="submit" value="Submit File" class="btn btn-default" />
                    </div>
                    <div class="progress" id="forProgressBar">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
                        <span id="progressStatus"></span>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="/source/js/functions.files.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    loadForms();
    
    $('#new-file-form').validate(
    {
        submitHandler: function()
        {
            submitFile('/my-files/newFileSubmit', 'new-file-form');
        }
    });
    
    function uploadComplete(res)
    {
        if(res === 'success')
        {
            $('#new-file').modal('hide');
            $('#name').val('');
            $('#file').val('');
            loadForms();
        }
        else
        {
            alert(res);
        }
    }
    
    function loadForms()
    {
        $('#my-file-wrapper').load('/myfiles/loadFiles/<?= $_SESSION['id'] ?>');
    }
</script>
