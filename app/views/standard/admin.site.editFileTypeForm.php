<form id="edit-file-type-form">
    <div class="form-group">
        <label for="typeName">Type Name:</label>
        <input type="text" name="typeName" id="typeName" class="form-control" value="<?= $data['description'] ?>" required />
    </div>
    <input type="submit" value="Edit File Type" class="form-control" />
</form>
<button class="btn btn-default btn-block pad-top" id="delete-file-type" data-value="<?= $data['typeID'] ?>">Delete File Type</button>