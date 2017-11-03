<div class="page-header">
    <h1 class="text-center">Reactivate Customer</h1>
</div>
<div class="table-responsive">
    <table class="table table-striped" id="customer-results">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th data-visible="false">City, State</th>
            </tr>
        </thead>
        <tbody>
            <?= $data['custList'] ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="text-center" id="modal-header">Modal Dialog</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <div id="yes-or-no-wrapper">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <h3 class="text-center">Please Confirm</h3>
                            <h4 class="text-center">This Action Cannot Be Undone</h4>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.reactivate-link').on('click', function()
                            {
        var custID = $(this).data('customer');
        $('.select-yes').on('click', function()
        {
            $.get('/admin/reactivateCustomerConfirm/'+custID, function(data)
            {
                if(data === 'success')
                {
                    location.reload();
                }
                else
                {
                    alert('There Was A Problem Reactivating This Customer');
                    $.post('/err/ajaxFail', {msg: data});
                }
            });
        });
    });
</script>
