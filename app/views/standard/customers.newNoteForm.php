<form id="add-note-form">
    <div class="form-group">
        <label for="noteSubject">Note Subject:</label>
        <input type="text" name="noteSubject" id="noteSubject" class="form-control" placeholder="Subject" />
    </div>
    <div class="form-group">
        <label for="noteDescription">Description:</label>
        <textarea name="noteDescription" id="noteDescription" class="form-control" rows="40" required></textarea>
    </div>
    <div class="form-group">
        <input type="submit" id="submit-new-note-btn" class="form-control btn btn-default" />
    </div>
</form>