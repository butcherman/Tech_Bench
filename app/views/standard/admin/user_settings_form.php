<div class="form-group">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" class="form-control" value="<?= $data['username']; ?>" required />
</div>
<div class="form-group">
    <label for="firstName">First Name:</label>
    <input type="text" name="firstName" id="firstName" class="form-control" value="<?= $data['first_name']; ?>" required />
</div>
<div class="form-group">
    <label for="lastName">Last Name:</label>
    <input type="text" name="lastName" id="lastName" class="form-control" value="<?= $data['last_name']; ?>" required />
</div>
<div class="form-group">
    <label for="email">Email Address:</label>
    <input type="email" name="email" id="email" class="form-control" value="<?= $data['email']; ?>" required />
</div>
<div class="form-group">
    <input type="submit" id="settings-form-submit" class="form-control btn btn-default" value="Update User Settings" />
</div>
