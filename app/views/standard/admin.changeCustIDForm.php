<div class="text-center">
    <h2 id="show-customer-id">Customer ID: <?= $data['custID']; ?></h2>
    <h2><span id="name-span"><?= $data['custName']; ?></span></h2>
    <h5 id="dba-span"><?= $data['dbaName']; ?></h5>
</div>
<div id="customer-address" class="text-center">
    <address>
        <?= $data['address']; ?>
    </address>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form id="change-cust-id">
            <div class="form-group">
                <label for="newid">New Cust ID:</label>
                <input type="text" id="newid" name="newid" class="form-control" placeholder="Enter A Valid Customer ID" />
            </div>
            <input type="submit" class="btn btn-default btn-block" value="Update Customer ID" />
        </form>
    </div>
</div>
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />
    </div>
</div>

<script>
    $('#change-cust-id').validate(
    {
        rules:
        {
            newid:
            {
                required: true,
                number: true
            }
        },
        submitHandler: function()
        {
            $.post('/admin/change-id-form-submit/<?= $data['custID']; ?>', $('#change-cust-id').serialize(), function(data)
            {
                alert(data);
            });
        }
    });
</script>