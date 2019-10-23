<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Tech Bench') }}</title>

    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">    
</head>
<body role="document">
    <div class="container-fluid" role="main" id="app">
        <div class="pb-2 mt-4 mb-2 border-bottom text-center">
        <h1>Error:</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="jumbotron text-center text-danger">
                    <img src="/img/err_img/sry_error.png" alt="Error Image" class="float-left" />
                    <h3 class="text-center">Javascript is Disabled</h3>
                    <br />
                    <p>
                        It appears that you have Javascript disabled.  {{config('app.name', 'Tech Bench')}} requires Javascript in order to function properly.  
                    </p>
                    <p>
                        Please enable Javascript and try again.
                    </p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div> 
    </div>
</body>
</html>
