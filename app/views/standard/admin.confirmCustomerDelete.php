<div class="text-center">
    <h2>Customer ID: <?= $data['custID']; ?></h2>
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
        <div class="jumbotron">
            <div id="yes-or-no-wrapper">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <h3 class="text-center">Please Confirm You Want To Delete This Customer</h3>
                        <h4 class="text-center">Deleting the customer will remove all files and information related to this customer.  This action cannot be undone.</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-sm-offset-4">
                        <button class="btn btn-default select-yes">Yes</button>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-default select-no" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <img src="/source/img/loader.gif" alt="loading..." class="loadingModal" />
    </div>
</div>

<script>
    $('.select-no').on('click', function()
    {
        parent.history.back();
        return false;
    });
    $('.select-yes').on('click', function()
    {
        $('#edit-modal').modal('show');
        $.get('/admin/deleteCustomerConfirmYes/<?= $data['custID']; ?>', function(data)
        {
            $('#edit-modal').modal('hide');
            if(data === 'success')
            {
                $('#yes-or-no-wrapper').html('<h2 class="text-center">Customer Deleted</h2>');
            }
            else
            {
                $('#yes-or-no-wrapper').html('<h2 class="text-center">There Was A Problem Deleting the Customer.  Please review logs for more information</h2>');
            }
        })
    });
</script>