<form id="edit-contact-form">
    <input type="hidden" id="contID" value="<?= $data['contID']; ?>" />
    <div class="form-group">
        <label for="contName">Contact Name:</label>
        <input type="text" name="contName" id="contName" class="form-control" value="<?= $data['name']; ?>" />
    </div>
    <div class="form-group">
        <label for="contEmail">Email Addresss:</label>
        <input type="email" name="contEmail" id="contEmail" class="form-control" value="<?= $data['email']; ?>" />
    </div>
    <fieldset class="form-group" id="edit-phone-numbers">
        <legend>Phone Numbers</legend>
        <?= $data['phoneNumbers'] ?>
    </fieldset>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <button class="btn btn-primary form-control pad-bottom" id="add-number">Add Number</button>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" id="submit-edit-contact" value="Edit Contact" class="btn btn-default btn-block" />
    </div>
</form>

<script>
    $('#add-number').on('click', function(e)
    {
        e.preventDefault();
        $('#edit-phone-numbers').append('<div class="row form-group"><div class="col-sm-3 col-sm-offset-1"><select id="numType" name="numType[]" class="form-control"><?= $data['numTypes'] ?></select></div><div class="col-sm-7"><input type="tel" name="contPhone[]" class="form-control" placeholder="Phone Number" /><span class="number-clear glyphicon glyphicon-remove-circle"></span></div></div>');
    });
    
    $(document).on('click', '.number-clear', function()
    {
        $(this).closest('.row').remove();
    });
</script>
