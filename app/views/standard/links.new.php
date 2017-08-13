<div class="page-header">
    <h1 class="text-center">Create New File Link</h1>
</div>
<div id="linkFormWrapper">
    <form id="new-file-link">
        <div class="form-group row">
            <label for="linkName" class="col-sm-2 col-sm-offset-1 col-form-label -text-right">Link Name</label>
            <div class="col-sm-6">
                <input type="text" name="linkName" id="linkName" class="form-control" required />
            </div>
        </div>
        <div class="form-group row">
            <label for="expire" class="col-sm-2 col-sm-offset-1 col-form-label -text-right">Link Expire Date</label>
            <div class="col-sm-6">
                <input type="date" name="expire" id="expire" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')); ?>" required />
            </div>
        </div>
        <div class="form-group row">
            <label for="file" class="col-sm-2 col-sm-offset-1 col-form-label -text-right">File</label>
            <div class="col-sm-6">
                <input type="file" name="file[]" id="file" class="form-control" multiple />
            </div>
        </div>
        <div class="checkbox row">
            <label for="allowUpload" class="col-sm-4 col-sm-offset-4 col-md-3 col-md-offset-5 col-form-label">
                <input type="checkbox" id="allowUpload" name="allowUpload" data-toggle="toggle" checked>
                Allow user to upload files
            </label>
        </div>
        <div class="form-group row">
            <div class="col-xs-2 col-md-offset-5 col-xs-offset-3">
                <input type="submit" id="createLink" class="btn btn-default" value="Create Link" />
            </div>
        </div>
        <div class="progress" id="forProgressBar">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
                <span id="progressStatus"></span>
            </div>
        </div>
    </form>
   <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <p>
                <strong>Instruction:</strong> Assign a name and experation date for the file link.  The name will display when the user visits the link so be PC!  The experation date is for security reasons and is set to be 30 days from today by default.  The experation date can be expanded now or later if necessary.
            </p>
            <p>
                It is optional to upload a file at this time.  If "Allow user to upload file" is off, the users will only be able to download files you have uploaded.
            </p>
        </div>
    </div> 
</div>

<script src="/source/js/functions.files.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
//  validate and create a new upload link
$('#new-file-link').validate(
{
    rules:
    {
        file: {
            filesize: maxFile
        }
    },
    messages:
    {
        file: "File size must be less than "+filesize(maxFile)
    },
    submitHandler: function()
    {
        if($('#file').val() == '')
        {
            $.post('/links/createSubmit', $('#new-file-link').serialize(), function(data)
            {
                uploadComplete(data);
            });
        }
        else
        {
            submitFile('/links/createSubmit', 'new-file-link');
        }
    }
});
//  After the upload link is created, navigate to that upload link page
function uploadComplete(res)
{
    window.location.replace('/links/details/'+res);
}
</script>