<form id="add-file-form">
    <div class="form-group">
        <label for="file">File</label>
        <input type="file" name="file" id="file" class="form-control" required />
    </div>
    <div class="form-group text-center">
        <input type="submit" id="submit-add-file" class="btn btn-default" value="Upload File" />
    </div>
    <div class="progress" id="forProgressBar">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
            <span id="progressStatus"></span>
        </div>
    </div>
</form>