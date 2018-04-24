<form id="email-file-link">
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="message">Message to user</label>
        <textarea name="message" id="message" class="form-control">
            <p>A file link has been created for you by <?= $_SESSION['name']; ?>.  Please follow the link below to gain access.</p>
            <p><?= $data['link']; ?></p>
        </textarea>
    </div>
    <div class="form-group">
        <input type="submit" id="send-email" class="btn btn-default form-control" value="Send Email" />
    </div>
</form>
