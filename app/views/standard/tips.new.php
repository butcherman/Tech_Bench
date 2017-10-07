<div class="page-header">
    <h1 class="text-center">New Tech Tip</h1>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form id="new-tech-tip">
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter A Descriptive Subject" required />
            </div>
            <div class="form-group">
                <label for="tipData">Tech Tip:</label>
                <textarea name="tipData" id="tipData" class="form-control" rows="20"></textarea>
            </div>
            <div class="form-group">
                <label for="tags">System Tags</label>
                <div id="tags" class="form-control tech-tip-tag-wrapper"></div>
            </div>
            <div class="form-group">
                <label for="selectSystem">Select Systems To Tag:</label>
                <select id="selectSystems" class="form-control">
                    <option></option>
                    <?= $data['optList']; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="file">File:</label>
                <input type="file" name="file[]" id="file" class="form-control" multiple />
            </div>
            <div class="progress" id="forProgressBar">
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
                    <span id="progressStatus"></span>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-default" value="Add Tech Tip" />
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="loadingModal">
    <div class="modal-dialog">      
        <img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />
    </div>
</div>

<script src="/source/lib/tinymce/tinymce.min.js"></script>
<script src="/source/lib/tinymce/plugins/autolink/plugin.min.js"></script>
<script src="/source/js/functions.files.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    tinymce.init(
    {
        selector: 'textarea',
        height: '500',
        plugins: 'autolink'
    });
    
    $('#selectSystems').on('change', function()
    {
        var value = $(this).val();
        var divVal = '<div class="tech-tip-tag" name="tipTag[]" data-value="'+value+'">'+value+' <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $('#tags').append(divVal);
    });
    
    $(document).on('click', '.close', function()
    {
        $(this).parent().remove();
    });
    
    $('#new-tech-tip').validate(
    {
        rules:
        {
            title: "required",
            tip: "required",
            "file[]": {
                filesize: maxFile
            }
        },
        messages:
        {
            "file[]": "File size must be less than "+filesize(maxFile)
        },
        submitHandler: function()
        {
            tinymce.triggerSave();
            $('.tech-tip-tag').each(function()
            {
                $('<input />').attr('type', 'hidden')
                    .attr('name', 'sysTags[]')
                    .attr('value', $(this).data('value'))
                    .appendTo('#new-tech-tip');
            });
            if($('#file').val() == '')
            {
                $('#loadingModal').modal('show');
                $.post('/tips/newTipSubmit', $('#new-tech-tip').serialize(), function(data)
                {
                    uploadComplete(data);
                });
            }
            else
            {
                submitFile('/tips/newTipSubmit', 'new-tech-tip');
                $('#loadingModal').modal('show');
            }
        }
    });
    
    function uploadComplete(res)
    {
        if($.isNumeric(res))
        {
            window.location.replace('/tips/id/'+res+'/'+$('#subject').val());
        }
        else
        {
            var msg = 'There was a problem adding the Tech Tip.\nPlease contact the system administrator';
            alert(msg+res);
        }
    }
</script>
