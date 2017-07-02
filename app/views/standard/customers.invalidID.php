<div class="container-fluid">
    <div class="page-header">
        <h1>Error:</h1>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="jumbotron text-center">
                <img src="/source/img/err_img/sry_error.png" alt="Error Image" class="pull-left" />
                <p>
                    We are sorry but the customer you are looking for does not exist or cannot be found.
                </p>
                <p>
                    A log has been generated and our minions are busy at work to determine what went wrong.
                </p>
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
</script>