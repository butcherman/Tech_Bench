<form id="new-file-form">
    <div class="form-group">
        <label for="file">Name:</label>
        <input type="text" name="fileName" id="fileName" class="form-control" placeholder="Subject" />
    </div>
    <div class="form-group">
        <label for="fileType">File Type</label>
        <select name="fileType" id="fileType" class="form-control">
            <?= $data['types']; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="file">File</label>
        <input type="file" name="file" id="file" class="form-control" />
    </div>
    <div class="form-group">
        <input type="submit" id="new-file-submit-lnk" class="form-control btn btn-default" />
    </div>
    <div class="progress" id="forProgressBar">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
            <span id="progressStatus"></span>
        </div>
    </div>
</form>