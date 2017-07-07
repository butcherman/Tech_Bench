<form id="edit-contact-form">
    <input type="hidden" id="contID" value="<?= $data['contID']; ?>" />
    <div class="form-group">
        <label for="contName">Contact Name:</label>
        <input type="text" name="contName" id="contName" class="form-control" value="<?= $data['name']; ?>" />
    </div>
    <div class="form-group">
        <label for="contPhone">Phone Number:</label>
        <input type="tel" name="contPhone" id="contPhone" class="form-control" value="<?= $data['phone']; ?>" />
    </div>
    <div class="form-group">
        <label for="contEmail">Email Addresss:</label>
        <input type="email" name="contEmail" id="contEmail" class="form-control" value="<?= $data['email']; ?>" />
    </div>
    <div class="form-group">
        <input type="submit" id="submit-edit-contact" value="Edit Contact" class="btn btn-default btn-block" />
    </div>
</form>