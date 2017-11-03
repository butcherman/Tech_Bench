<form id="add-contact-form">
    <div class="form-group">
        <label for="contName">Contact Name:</label>
        <input type="text" name="contName" id="contName" class="form-control" />
    </div>
    <div class="form-group">
        <label for="contEmail">Email Addresss:</label>
        <input type="email" name="contEmail" id="contEmail" class="form-control" />
    </div>
    <fieldset class="form-group" id="phone-numbers">
        <legend>Phone Numbers</legend>
        <div class="row form-group">
            <div class="col-sm-3 col-sm-offset-1">
                <select id="numType" name="numType[]" class="form-control">
                    <?= $data['numTypes'] ?>
                </select>
            </div>
            <div class="col-sm-7">
                <input type="tel" name="contPhone[]" class="form-control" placeholder="Phone Number" />
            </div>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <button class="btn btn-primary form-control pad-bottom" id="add-number">Add Number</button>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" id="submit-new-contact" value="Add Contact" class="btn btn-default btn-block form-control" />
    </div>
</form>

<script>
    $('#add-number').on('click', function(e)
    {
        e.preventDefault();
        $('#phone-numbers').append('<div class="row form-group"><div class="col-sm-3 col-sm-offset-1"><select id="numType" name="numType[]" class="form-control"><?= $data['numTypes'] ?></select></div><div class="col-sm-7"><input type="tel" name="contPhone[]" class="form-control" placeholder="Phone Number" /><span class="number-clear glyphicon glyphicon-remove-circle"></span></div></div>');
    });
    
    $(document).on('click', '.number-clear', function()
    {
        $(this).closest('.row').remove();
    });
</script>
