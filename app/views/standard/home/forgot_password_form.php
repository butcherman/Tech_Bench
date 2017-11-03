<div class="reset-container">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <h1 class="text-center">Reset Password</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h3 id="form-error" class="text-center"><img src="/source/img/loader.gif" alt="loading..." class="loadingModal" /></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-signin" id="forgot-password-form">
                <h2 class="text-center">Enter The Following Information</h2>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required>
                <label for="submit"></label>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            <p>
                <strong>Note: </strong>Only one link can be created.  Trying to create a second link will result in an error.
            </p>
            <p>Links will only be available for 24 hours.</p>
        </div>
    </div>
</div>    
<div class="modal fade" id="loadingModal">
    <div class="modal-dialog">      
        <img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />
    </div>
</div>

<script src="/source/js/functions.login.js"></script>
