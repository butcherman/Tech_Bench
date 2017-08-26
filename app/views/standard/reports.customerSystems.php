<div class="page-header">
    <h1 class="text-center">Customer System Report</h1>
</div>
<div>
    <form id="customer-search-form">
        <div class="form-group row">
            <label for="customer" class="col-md-2 text-right">Search Customers By:</label>
            <div class="col-md-9">
                <input type="text" name="customer" id="customer" class="form-control" placeholder="Customer Name" />
            </div>
            <div class="col-md-1">
                <button type="submit" class="form-control"><span class="glyphicon glyphicon-search"></span></button>
            </div>
        </div>
    </form>
</div>
<div id="cust-backup-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Has A System</th>
            </tr>
        </thead>
        <tbody id="cust-list"></tbody>
    </table>
</div>

<script>
    function search()
    {
        $.post('/reports/searchCustSystems', $('#customer-search-form').serialize())
         .done(function(data)
         {
            $('#cust-list').html(data);
         });
    }
    
    search();
    
    $('#customer').on('keyup', function()
    {
        search();
    });
</script>