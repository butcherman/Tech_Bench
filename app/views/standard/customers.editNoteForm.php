<form id="edit-note-form">
    <div class="form-group">
        <label for="noteSubject">Note Subject:</label>
        <input type="text" name="noteSubject" id="noteSubject" class="form-control" placeholder="Subject" value="<?= $data['subject']; ?>" />
    </div>
    <div class="form-group">
        <label for="noteDescription">Description:</label>
        <textarea name="noteDescription" id="noteDescription" class="form-control" rows="40" required><?= $data['body']; ?></textarea>
    </div>
    <div class="form-group">
        <input type="submit" id="submit-edit-note-btn" class="form-control btn btn-default" />
    </div>
</form>