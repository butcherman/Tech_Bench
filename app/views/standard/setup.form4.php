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
            <form id="step4">
                <div class="form-group">
                    <label for="fileLocation">File Location</label>
                    <input type="text" id="fileLocation" name="fileLocation" class="form-control" value="<?= $data['fileLocal']; ?>" placeholder="Enter The Root Location All Files Should Be Stored" />
                </div>
                <div class="form-group">
                    <label for="maxSize">Max File Size</label>
                    <input type="range" id="maxSize" name="maxSize" class="form-control" min="100" max="2000" />
                </div>
                
                
                <div class="form-group">
                    <input type="submit" id="step-1-submit" class="form-control btn btn-default" value="Finish Tech Bench Setup" />
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#step4').validate(
    {
        submitHandler: function()
        {
            $.post('/setup/submit', $('#step4').serialize(), function(data)
            {
                if(data == 'success')
                {
                    window.location.replace('/setup/step-2');
                }
            });
        }
    });
</script>