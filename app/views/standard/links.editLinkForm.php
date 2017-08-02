<form id="edit-file-link">
    <div class="form-group">
        <label for="linkName">Link Name</label>
        <input type="text" name="linkName" id="linkName" class="form-control" value="<?= $data['name']; ?>" required />
    </div>
    <div class="form-group">
        <label for="expire">Link Expire Date</label>
        <input type="date" name="expire" id="expire" class="form-control" value="<?= date('Y-m-d', strtotime($data['expire'])); ?>" required />
    </div>
    <div class="checkbox row">
        <label for="allowUpload" class="col-sm-6 col-sm-offset-3">
            <input type="checkbox" id="allowUpload" name="allowUpload" data-toggle="toggle" <?= $data['allow']; ?>>
            Allow user to upload files
        </label>
    </div>
    <div class="form-group">
        <input type="submit" id="updateLink" class="btn btn-default form-control" value="Update Link" />
    </div>
</form>