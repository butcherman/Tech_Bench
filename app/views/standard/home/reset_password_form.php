<div class="page-header">
    <h1 class="text-center">Reset Password</h1>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3 class="text-center hide-text" id="form-error"></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form id="reset-password-form">
            <input type="hidden" name="link" value="<?= $data['link']; ?>" />
            <div class="form-group">
                <label for="newPass">New Password</label>
                <input type="password" id="newPass" name="newPass" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="confPass">Confirm Password</label>
                <input type="password" id="confPass" name="confPass" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-block btn-default"  value="Update Password" />
            </div>
        </form>
    </div>
</div>

<script src="/source/js/functions.login.js"></script>
