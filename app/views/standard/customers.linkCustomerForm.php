<form id="add-linked-site-form">
    <div class="checkbox row">
        <label for="thisIsParent" class="col-sm-5 col-sm-offset-3 col-form-label">
            <input type="checkbox" id="thisIsParent" name="thisIsParent" data-toggle="toggle">
            This is the parent site
        </label>
    </div>
    <div class="form-group" id="parent-id-selection">
        <label for="customerParent">Parent Customer Site ID</label>
        <input type="text" name="customerParent" id="customerParent" class="form-control" placeholder="Enter A Valid Customer Site ID">
    </div>
    <input type="submit" class="btn btn-default btn-block" value="Submit" />
</form>
<div class="row pad-top">
    <div class="col-md-8 col-md-offset-2">
        <p><strong>Note:</strong> You must enter a valid parent site ID.  If you have not flagged a site as a parent site, please do so before linking a site to it.</p>
    </div>
</div>
