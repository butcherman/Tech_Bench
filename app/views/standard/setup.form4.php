<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h1 class="text-center">File Information</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="jumbotron">
            <div class="breadcrumb-outer-wrapper">
                <div class="breadcrumb-inner-wrapper">
                    <a href="#" class="breadcrumb-link active">Step 1</a>
                    <a href="#" class="breadcrumb-link active">Step 2</a>
                    <a href="#" class="breadcrumb-link active">Step 3</a>
                    <a href="#" class="breadcrumb-link active">Step 4</a>
                    <a href="#" class="breadcrumb-link">Step 5</a>
                </div>
            </div>
            <div class="clearfix pad-bottom"></div>
            <form id="step4">
                <div class="form-group">
                    <label for="fileLocation">Root File Location:</label>
                    <input type="text" id="fileLocation" name="fileLocation" class="form-control" value="<?= $data['fileLocal']; ?>" placeholder="Enter The Root Location All Files Should Be Stored" />
                </div>
                <div class="form-group">
                    <label for="maxSize">Max File Size:</label>
                    <span id="maxValue"></span>
                    <div>
                        <input type="text" id="maxFileValue" name="maxFileValue"  
                               data-slider-id="maxFileValue" 
                               data-slider-min="100000" 
                               data-slider-max="<?= $data['maxFile']; ?>"
                               data-slider-step="1000" 
                               data-slider-value="<?= $data['maxFile']; ?>"
                               data-slider-tooltip="hide"/>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" id="step-1-submit" class="form-control btn btn-default" value="Finish Tech Bench Setup" />
                </div>
            </form>
            <div>
                <h4 class="text-center">Tips:</h4>
                <ul>
                    <li><strong>Root File Location:</strong> Enter the base location that all files will be stored.  Only change if you know that the required folder has write access.</li>
                    <li><strong>Max File Size:</strong> Select the maximum size that is allowed to be uploaded by a user.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<link href="/source/lib/bootstrap-slider/css/bootstrap-slider.min.css" type="text/css" rel="stylesheet">
<script src="/source/lib/bootstrap-slider/bootstrap-slider.min.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    $('#maxFileValue').slider();
    $('#maxFileValue').on('slide', function(e)
    {
        var file = e.value;
        $('#maxValue').text(filesize(file));
    });
    
    var file = <?= $data['maxFile']; ?>;
    $('#maxValue').text(filesize(file));
    
    $('#step4').validate(
    {
        submitHandler: function()
        {
            $.post('/setup/submit', $('#step4').serialize(), function(data)
            {
                if(data == 'success')
                {
                    window.location.replace('/setup/finish');
                }
            });
        }
    });
</script>