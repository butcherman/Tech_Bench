<div class="container-fluid">
    <div>
        <h2><span id="tip-fav" class="glyphicon glyphicon-bookmark <?= $data['tipFav']; ?>" title="Bookmark Tech Tip" data-tooltip="tooltip"></span><?= $data['title']; ?></h2>
    </div>
    <div class="row">
        <div class="col-sm-8 tech-tip-details">
            <span><strong>ID#: </strong> <?= $data['tipID']; ?></span>
            <span><strong>Author:</strong> <?= $data['author']; ?></span>
            <span><strong>Date:</strong> <?= $data['date']; ?></span>
            <span><strong>Tags:</strong> <span><?= $data['tipTags']; ?></span></span>
        </div>
    </div>
    <div class="row" id="tip-wrapper">
        <div class="col-sm-12">
            <?= $data['tip']; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1 tip-files">
            <h4>Attachments:</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <ul>
                <?= $data['files']; ?>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1 tip-files">
            <h4>Comments:</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table id="tech-tip-comments"></table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-3">
            <?= $data['editLink']; ?>
        </div>
        <div class="col-md-3">
            <?= $data['deleteLink']; ?>
        </div>
    </div>
</div>

<script>
    $('#tech-tip-comments').load('/tips/getComments/<?= $data['tipID']; ?>');
    //  Add or remove a bookmark for a customer
    $('#tip-fav').click(function()
    {
        if($(this).hasClass('item-fav-unchecked'))
        {
            $(this).removeClass('item-fav-unchecked');
            $(this).addClass('item-fav-checked');
        }
        else
        {
            $(this).removeClass('item-fav-checked');
            $(this).addClass('item-fav-unchecked');
        }

        $.get('/tips/toggleFav/<?= $data['tipID']; ?>');
    });
    //  Open the Add Comment box
    $(document).on('click', '#add-tip-comment', function(e)
    {
        e.preventDefault();
        $('#show-comment-form').removeClass('hidden');
        $(this).hide();
    });
    //  Validate the add comment form
    $(document).on('click', '#submit-comment-form', function()
    {
        $('#tech-tip-comment-form').validate(
        {
            rules:
            {
                commentInput: "required"
            },
            submitHandler: function()
            {
                $.post('/tips/addComment/<?= $data['tipID']; ?>', $('#tech-tip-comment-form').serialize(), function(data)
                {
                    if(data === 'success')
                    {
                        $('#tech-tip-comments').load('/tips/getComments/<?= $data['tipID']; ?>');
                    }
                    else
                    {
                        alert('Unable to Add Comment');
                        $.post('/err/ajaxFail', {msg: data});
                    }
                });
            }
        });
    });
    
</script>
