<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/favicon.ico" type="image/x-icon" rel="icon">
    <link href="/source/lib/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="/source/lib/bootstrap-toggle/bootstrap-toggle.min.css" type="text/css" rel="stylesheet">
    <link href="/source/css/standard.css" type="text/css" rel="stylesheet">

    <script src="/source/lib/jquery/jquery.min.js"></script>
    <script src="/source/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="/source/lib/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="/source/lib/jquery-validation/jquery.validate.min.js"></script>
    <script src="/source/lib/jquery-validation/additional-methods.min.js"></script>
    <script>var maxFile = <?= Config::getFile('maxUpload'); ?></script>

    <title><?= Config::getCore('title'); ?></title>
</head>
<body role="document">
    <noscript>
        <meta http-equiv="refresh" content="0; URL=/err/no-script">
        <h1 class="text-center">You Must Enable Javascript to Use This Site</h1>
    </noscript>
    <div class="container-fluid" role="main">
        <?= $content; ?>
    </div>
</body>
</html>
