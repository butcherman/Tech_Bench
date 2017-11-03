<div class="row">
    <div class="col-md-10 col-md-offset-1">
         <form id="share-form">
            <h3 class="text-center">Select User to Share Link With</h3>
            <div class="form-group">
                <select id="selectUser" name="selectUser" class="form-control">
                    <?= $data['optList']; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default btn-block" id="submit-share-form" value="Share Link" />
             </div>
        </form>
    </div>
</div>
