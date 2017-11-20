<form id="customer-edit-form">
<div class="form-group">
    <label for="custName">Customer Name:</label>
    <input type="text" name="custName" id="custName" class="form-control" value="<?= $data['custName']; ?>" />
</div>
<div class="form-group">
    <label for="custDBA">DBA Name/AKA:</label>
    <input type="text" name="custDBA" id="custDBA" class="form-control" value="<?= $data['dbaName']; ?>" />
</div>
<div class="form-group">
    <label for="custAddr">Address:</label>
    <input type="text" name="custAddr" id="custAddr" class="form-control" value="<?= $data['address']; ?>" />
</div>
<div class="form-group">
    <label for="custCity">City:</label>
    <input type="text" name="custCity" id="custCity" class="form-control" value="<?= $data['city']; ?>" />
</div>
<div class="form-group">
    <label for="state">State:</label>
    <select name="state" id="state" class="form-control">
        <option value="AL">AL</option>
        <option value="AK">AK</option>
        <option value="AZ">AZ</option>
        <option value="AR">AR</option>
        <option value="CA">CA</option>
        <option value="CO">CO</option>
        <option value="CT">CT</option>
        <option value="DE">DE</option>
        <option value="FL">FL</option>
        <option value="GA">GA</option>
        <option value="HI">HI</option>
        <option value="ID">ID</option>
        <option value="IL">IL</option>
        <option value="IN">IN</option>
        <option value="IA">IA</option>
        <option value="KS">KS</option>
        <option value="KY">KY</option>
        <option value="LA">LA</option>
        <option value="ME">ME</option>
        <option value="MD">MD</option>
        <option value="MA">MA</option>
        <option value="MI">MI</option>
        <option value="MN">MS</option>
        <option value="MS">MS</option>
        <option value="MO">MO</option>
        <option value="MT">MT</option>
        <option value="NE">NE</option>
        <option value="NV">NV</option>
        <option value="NH">NH</option>
        <option value="NJ">NJ</option>
        <option value="NM">NM</option>
        <option value="NY">NY</option>
        <option value="NC">NC</option>
        <option value="ND">ND</option>
        <option value="OH">OH</option>
        <option value="OK">OK</option>
        <option value="OR">OR</option>
        <option value="PA">PA</option>
        <option value="RI">RI</option>
        <option value="SC">SC</option>
        <option value="SD">SD</option>
        <option value="TN">TN</option>
        <option value="TX">TX</option>
        <option value="UT">UT</option>
        <option value="VA">VA</option>
        <option value="WA">WA</option>
        <option value="WI">WI</option>
        <option value="WY">WY</option>
    </select>
</div>
<div class="form-group">
    <label for="zipCode">Zip Code:</label>
    <input type="number" name="zipCode" id="zipCode" class="form-control" maxlength="5" minlength="5" value="<?= $data['zip']; ?>" />
</div>
<div class="form-group">
    <input type="submit" id="submit-cust-edit" class="form-control" value="Update Customer" />
</div>
</form>

<script>
    $('#state').val('<?= $data['state']; ?>')
</script>
