<div class="container-fluid">
    <div class="page-header text-center">
        <h1><?= str_replace('-', ' ', $data['sysName']); ?></h1>
    </div>
    <?= $data['fileList']; ?> 
    <?= $data['fileData']; ?>
    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            <a href="#new-file" data-toggle="modal" class="btn btn-default btn-block">Add File</a>
        </div>
    </div>
</div>

<div class="modal fade" id="new-file">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4>Add System File:</h4>
            </div>
            <div class="modal-body">
                <form id="new-file-form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Descriptive Name For File" autofocus required />
                    </div>
                    <div class="form-group">
                        <label for="fileType">File Type:</label>
                        <select name="fileType" id="fileType" class="form-control">
                            <?= $data['optList']; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="file">File:</label>
                        <input type="file" name="file" id="file" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" placeholder="A Brief Description of The File" rows="5" class="form-control"></textarea>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="/source/js/functions.files.js"></script>
<script>
    $('body').popover({
        selector: '[data-toggle="popover"]',
        trigger: 'hover'
    });
    
    function loadDocs()
    {
        $('.ajax-table').each(function()
        {
            var load = $(this).data('load');
            $(this).children('tbody').load('/system/getSysFiles/<?= $data['sysName']; ?>/'+load);
        });
    }
    
    loadDocs();
    
    $('#new-file').on('show.bs.modal', function()
    {
        var type = $('.tab-content .active').attr('id');
        $('#fileType').val(type);
    });
    
    $('#new-file').on('hide.bs.modal', function()
    {
        $('#new-file-form').find('input[type=text], intput[type=file], textarea').val('');
    });
    
    $('#file').on('change', function()
    {
        var fileName = $('input[type=file]').val().split('\\').pop();
        if (!$("#name").val())
        {
            $("#name").val(fileName);
        }
    });
    
    $('#new-file-form').validate(
    {
        rules:
        {
            name: "required",
            file:
            {
                filesize: maxFile,
                required: true
            }
        },
        messages:
        {
            name: "Please Give A Descriptive Name For The File",
            file: 
            {
                filesize: "File size must be less than "+maxFile,
                required: "Please Select A File"
            }
        },
        submitHandler: function()
        {
            submitFile('/system/submitNewFile/<?= $data['sysName']; ?>', 'new-file-form');
        }
    });
    
    function uploadComplete(res)
    {
        if(res != 1)
        {
            alert('There Was A Problem Uploading Your File\nA Log Has Been Generated');
        }
        var load = $('.tab-content .active .ajax-table').data('load');
        $('#new-file').modal('hide');
        $('.tab-content .active .ajax-table').children('tbody').load('/system/getSysFiles/<?= $data['sysName']; ?>/'+load);
    }
</script>
