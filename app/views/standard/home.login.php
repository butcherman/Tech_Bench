<div class="row">
    <div class="col-sm-4 col-sm-offset-4">
        <h1 class="text-center"><?= Config::getCore('companyName') ?></h1>
        <img src="/source/img/<?= Config::getCore('logo'); ?>" alt="Company Logo" class="text-center" id="header-logo" />
        <h1 class="text-center">Tech Bench</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h3 id="form-error" class="text-center">Invalid Username or Password</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form class="form-signin" id="login-form">
            <h2 class="text-center">Login</h2>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="remember" name="remember" value="remember-me"> Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
        <h3 class="text-center"><a href="/home/forgot-password">Forgot Password?</a></h3>
    </div>
</div>    

<script src="/source/js/functions.login.js"></script>
