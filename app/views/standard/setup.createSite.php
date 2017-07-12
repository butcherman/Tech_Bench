<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h1 class="text-center">Creating Site</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="jumbotron" id="install-process">
            <h3 class="text-center">Please wait while we build the Tech Bench.</h3>
        </div>
    </div>
</div>

<script>
    var steps = [
        'configuration file',
        'new database',
        'defaults',
        'directory structure'
    ];
    
    $('#install-process').html('<h3 class="text-center">Creating Site</h3>');
    buildSite(0);
    
    function buildSite(key)
    {
        var step = steps[key];
        var str = 'Creating '+step;
        
        $('#install-process').append('<div class="install-step"><img src="/source/img/loader.gif" alt="loading" id="img-'+key+'" /><span id="msg-'+key+'">'+str+'</span></div>');
        
        $.post('/setup/create/'+step, dataArr, function(data)
        {
            if(data === 'success')
            {
                $('#img-'+key).attr('src', '/source/img/go.png');
            }
            else
            {
                $('#img-'+key).attr('src', '/source/img/stop.png');
                $('#msg-'+key).html(data);
            }
            
            if(key+1 < steps.length && data === 'success')
            {
                buildSite(key+1);
            }
            else if(data != 'success')
            {
                $('#install-process').append('<h3 class="text-center">Setup Failed</h3><h4 class="text-center">Use The Back Button on Your Browser to Go Back to the Setup Form</h4>');
            }
            else
            {
                $('#install-process').append('<h3 class="text-center">Tech Bench Database Has Been Created</h3><h4 class="text-center"><a href="/site-administration">Click to Setup Systems</a></h4>');
            }
        });
    }
</script>