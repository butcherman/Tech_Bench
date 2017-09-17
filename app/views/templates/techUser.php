<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="/favicon.ico" type="image/x-icon" rel="icon">
        <link href="/source/lib/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="/source/lib/bootstrap-toggle/bootstrap-toggle.min.css" type="text/css" rel="stylesheet">
        <link href="/source/css/techStyles.css" type="text/css" rel="stylesheet"> 
        
        <script src="/source/lib/jquery/jquery.min.js"></script>
        <script src="/source/lib/bootstrap/js/bootstrap.min.js"></script>
        <script src="/source/lib/bootstrap-toggle/bootstrap-toggle.min.js"></script>
        <script src="/source/lib/jquery-validation/jquery.validate.js"></script>
        <script src="/source/lib/jquery-validation/additional-methods.js"></script>
        <script src="/source/js/functions.tech.js"></script>
        <script>var maxFile = <?= Config::getFile('maxUpload'); ?></script>
        
        <title><?= Config::getCore('title'); ?></title>
    </head>
    <body role="document">
        <header class="main-header">
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <a href="/tech" class="navbar-brand">Tech Bench</a>
                <button type="button" class="navbar-toggle collapsed" id="menu-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-title centerBlock">
                    <?= Template::inMaintenanceMode() ? 'Maintenance Mode' : 'Tech Bench'; ?>
                    <a href="/about" title="About Tech Bench"><span class="glyphicon glyphicon-info-sign pull-right" style="color: white"></span></a>
                </span>
            </nav>
        </header>
        <div id="wrapper">
            <div id="sidebar-wrapper">
                <div id="user-data">
                    <p>
                        <a href="/dashboard">
                            <span class="glyphicon glyphicon-user"></span>
                            <span id="nav-user-name"><?= $_SESSION['name']; ?></span>
                            (<span id="nav-user-notifications"><?= Template::getNotifications(); ?></span>)  
                        </a>
                    </p>
                </div>
                <div class="sidebar-navigation">
                    <h3>Dashboard</h3>
                    <ul class="nav-toggle">
                        <li><span class="glyphicon glyphicon-home"></span> <a href="/dashboard">Dashboard Home</a></li>
                        <li><span class="glyphicon glyphicon-link"></span> <a href="/links">File Links</a></li>
                        <li><span class="glyphicon glyphicon-file"></span> <a href="/forms">Company Forms</a></li>
                    </ul>
                    <?= Template::getAdminLinks(); ?>
                    <span id="systemNavLinks"><?= Template::getSysLinks(); ?></span>
                    <h3>Customers</h3>
                    <ul class="nav-toggle">
                        <li><span class="glyphicon glyphicon-search"></span> <a href="/customer">Search Customers</a></li>
                        <li><span class="glyphicon glyphicon-plus"></span> <a href="/customer/add">Add Customer</a></li>
                    </ul>
                    <h3>Tech Tips</h3>
                    <ul class="nav-toggle">
                        <li><span class="glyphicon glyphicon-search"></span> <a href="/tips">View Tech Tips</a></li>
                        <li><span class="glyphicon glyphicon-plus"></span> <a href="/tips/new-tip">New Tech Tip</a></li>
                    </ul>
                    <h3>Account</h3>
                    <ul class="nav-toggle">
                        <li><span class="glyphicon glyphicon-user"></span> <a href="/account">Settings</a></li>
                        <li><span class="glyphicon glyphicon-log-out"></span> <a href="/logout">Logout</a></li>
                    </ul>
                    <h3 class="top-buffer">&nbsp;</h3>
                </div>
            </div>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <?= $content; ?>
                </div>
            </div>
        </div>
    </body>
</html>
