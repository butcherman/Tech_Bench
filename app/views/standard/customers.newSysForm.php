<form id="add-system-form">
    <div class="form-group">
        <label for="addSystemType">SELECT SYSTEM TYPE:</label>
        <select name="addSystemType" id="addSystemType" class="form-control">
            <option value="">Select System</option>
            <?= $data['optList']; ?>
        </select>
    </div>
    <div id="add-system-data"></div>
    <div class="form-group">
        <input type="submit" class="form-control btn btn-default" value="Add System" disabled />
    </div>
</form>