<div class="page-header">
    <h1 class="text-center">Database Check</h1>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <a href="#" id="start-check" class="btn btn-default btn-block">Run Database Check</a>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-4 col-md-offset-4">
        <p>
            Database check will check both the database for proper integrity, and the file system to make susre all proper folders are created, and files are in place.
        </p>
    </div>
</div>
<div class="row pad-top">
    <div class="col-md-8 col-md-offset-2">
        <div class="jumbotron" id="backup-status-wraper">
            <div id="status"></div>
        </div>
    </div>
</div>


<script>
    $('#start-check').on('click', function(e)
    {
        e.preventDefault();
        var steps = [
//            'systems',
//            'customers',
//            'tech-tips',
            'file-links'
            
            
            
//            'database-tables',

        ];
        $('#backup-status-wraper').show();
        checkApplication(0);
        
        function checkApplication(key)
        {
            var step = steps[key];
            var str = 'Working On '+step.replace('-', ' ');
            
            $('#status').append('<div class="process-step"><img src="/source/img/loader.gif" alt="loading" id="img-'+key+'" /><span id="msg-'+key+'">'+str+'</span></div>');
            
            $.get('/maintenance/check-'+step, function(data)
            {
                if(data === 'success')
                {
                    $('#img-'+key).attr('src', '/source/img/go.png');
                    $('#msg-'+key).append(' Completed');
                    if(key+1 < steps.length)
                    {
                        checkApplication(key+1);
                    }
                }
                else if(data === 'error')
                {
                    $('#img-'+key).attr('src', '/source/img/stop.png');
                    $('#msg-'+key).append(' Error!  System Checked Halted');
                }
                else
                {
                    $('#img-'+key).attr('src', '/source/img/warning.png');
                    $('#msg-'+key).append('<div class="process-error">'+data+'</div>');
                    if(key+1 < steps.length)
                    {
                        checkApplication(key+1);
                    }
                }
            });
        }
    });
</script>
