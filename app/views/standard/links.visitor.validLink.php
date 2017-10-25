<div class="container">
    <div class="page-header">
        <h1>
            <img src="/source/img/<?= Config::getCore('logo'); ?>" alt="Tech Bench" class="upload-logo" />
            Tech Bench
        </h1> 
    </div>
    <div class="jumbotron">
        <div class="page-header">
            <h1 class="text-center">File Link</h1>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2 link-instructions-wrapper"><?= $data['instructions']; ?></div>
        </div>
        <div id="fileWrapper">
            <div class="page-header">
                <h2>You Have Files Available For Download:</h2>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>File Name:</th>
                            <th>Date Added:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $data['files']; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="newFileWrapper">
            <div class="page-header">
                <h2>Add A File:</h2>
            </div>
            <div id="updateNotice" class="text-center hidden">
                <h3>File Successfully Added</h3>
                <h4>You Can Add Additional Files If Necessary</h4>
            </div>
            <form id="customer-add-file">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="file">File:</label>
                    <input type="file" name="file[]" id="file" class="form-control" multiple required />
                </div>
                <div class="form-group">
                    <label for="notes">Notes For File:</label>
                    <textarea name="notes" id="notes" class="form-control" rows="10"></textarea>
                </div>
                <div class="form-group text-center">
                    <input type="submit" id="submit" value="Submit File" class="btn btn-default" />
                </div>
                <div class="progress" id="forProgressBar">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" id="progressBar">
                        <span id="progressStatus"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/source/lib/tinymce/tinymce.min.js"></script>
<script src="/source/js/functions.files.js"></script>
<script src="/source/lib/filesize/filesize.min.js"></script>
<script>
    if($('.link-instructions-wrapper').is(':empty'))
    {
        $('.link-instructions-wrapper').addClass('hide');
    }
    jQuery.validator.addMethod("notEqualTo", function(value, element, param) {
        return this.optional(element) || value != $(param).val();
    }, "This has to be different...");
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than {0}');
    tinymce.init(
    { 
        selector:'textarea',
        height: '400'
    });
    if('<?= $data['files']; ?>' == '')
    {
        $('#fileWrapper').hide();
    }
    if('<?= $data['allow']; ?>' != '1')
    {
        $('#newFileWrapper').hide();
    }
    
    var maxFile = <?= Config::getFile('maxUpload'); ?>;
    $('#customer-add-file').validate(
    {
        rules:
        {
            file:
            {
                filesize: maxFile
            }
        },
        messages:
        {
            file: { 
                filesize: "File size must be less than "+filesize(maxFile)
            }
        },
        submitHandler: function()
        {
            tinymce.triggerSave();
            submitFile('/fileLink/submitNewFile/<?= $data['linkID']; ?>', 'customer-add-file')
        }
    });
    function uploadComplete(res)
    {
        if(res === 'success')
        {
            $('#updateNotice').removeClass('hidden');
            $('#forProgressBar').hide();
            $('#progressBar').css('width', '0');
            $('#customer-add-file').find('input[type=file]').val('');
            tinymce.activeEditor.setContent('');
        }
        else
        {
            alert(res);
            $.post('/err/ajaxFail', {msg: res});
        }
    }
</script>
