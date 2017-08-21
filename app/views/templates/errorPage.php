
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/favicon.ico" type="image/x-icon" rel="icon">
    <link href="/source/lib/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="/source/css/standard.css" type="text/css" rel="stylesheet">

    <script src="/source/lib/jquery/jquery.min.js"></script>
    <script src="/source/lib/bootstrap/js/bootstrap.min.js"></script>

    <title>Error!</title>
</head>
<body role="document">
    <div class="container-fluid" role="main">
        <div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">Well, This Is Embarrassing</h1>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="jumbotron text-center">
                <?= $content; ?>
                <button class="btn btn-default" id="goBack">Click To Go Back</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>   
</div>

<script>
    $('#goBack').click(function()
    {
        parent.history.back();
        return false;
    });
</script>    </div>
</body>
</html>
