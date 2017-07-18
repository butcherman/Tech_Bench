<div class="container-fluid">
    <div>
        <h2><span id="tip-fav" class="glyphicon glyphicon-bookmark <?= $data['tipFav']; ?>" title="Bookmark Tech Tip" data-tooltip="tooltip"></span><?= $data['title']; ?></h2>
    </div>
    <div class="row">
        <div class="col-sm-8 tech-tip-details">
            <span class="first"><strong>ID#: </strong> <?= $data['tipID']; ?></span>
            <span><strong>Author:</strong> <?= $data['author']; ?></span>
            <span><strong>Date:</strong> <?= $data['date']; ?></span>
            <span><strong>Tags:</strong> <?= $data['tipTags']; ?></span>
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
</div>

<script>
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
</script>