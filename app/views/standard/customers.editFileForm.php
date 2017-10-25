<form id="edit-file-form">
    <div class="form-group">
        <label for="file">Name:</label>
        <input type="text" name="fileName" id="fileName" class="form-control" placeholder="Subject" value="<?= $data['name']; ?>" />
    </div>
    <div class="form-group">
        <label for="fileType">File Type</label>
        <select name="fileType" id="fileType" class="form-control">
            <?= $data['types']; ?>
        </select>
    </div>
    <div class="form-group">
        <input type="submit" id="edit-file-submit-lnk" class="form-control btn btn-default" />
    </div>
</form>
