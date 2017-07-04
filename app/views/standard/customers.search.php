<div class="container-fluid">
    <div class="page-header text-center">
        <h1>Customers</h1>
    </div>
    <div>
        <form id="customer-search-form">
            <div class="form-group row">
                <label for="customer" class="col-md-2 text-right">Search Customers By:</label>
                <div class="col-md-3">
                    <input type="text" name="customer" id="customer" class="form-control" placeholder="Customer Name" />
                </div>
                <div class="col-md-3">
                    <input type="text" name="city" id="city" class="form-control" placeholder="City" />
                </div>
                <div class="col-md-3">
                    <select name="systemType" id="systemType" class="form-control">
                        <option value="">Select System Type</option>
                        <?php echo $data['optList']; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="form-control"><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped" id="customer-results">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>City, State</th>
                    <th>System Type</th>
            <!--    <th>Backup Loaded</th>  -->
                </tr>
            </thead>
            <tfoot>
                <tr id="pager" class="form-horizontal">
                    <td colspan="3" class="text-center">
                        <button type="button" class="btn first"><span class="glyphicon glyphicon-step-backward"></span></button>
                        <button type="button" class="btn prev"><span class="glyphicon glyphicon-backward"></span></button>
                        <span class="pagedisplay"></span>
                        <button type="button" class="btn next"><span class="glyphicon glyphicon-forward"></span></button>
                        <button type="button" class="btn last"><span class="glyphicon glyphicon-step-forward"></span></button>
                        <select class="pagesize input-mini" title="Select page size">
                            <option selected="selected" value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>   
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td colspan="3" id="results-header" class="text-center">Search For Customer By Name, City, Or System Type</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="/source/lib/tablesorter/jquery.tablesorter.combined.min.js"></script>
<script src="/source/lib/tablesorter/jquery.tablesorter.pager.min.js"></script>

<script>
    function search()
    {
        $.post('/customer/searchForm', $('#customer-search-form').serialize())
         .done(function(data)
         {
            $('#customer-results > tbody').html(data);
            $('#customer-results').trigger('update');
         });
    }
    
    search();
    
    $('#customer-results').tablesorter(
    {
        theme : "bootstrap",
        headerTemplate : '{content} {icon}', 
        widgets : [ "uitheme", "zebra" ],
        widgetOptions : {
            zebra : ["even", "odd"],
        }
    }).tablesorterPager(
    {
        output: '{startRow} to {endRow} ({totalRows} Results Found)',
        container: $("#pager")
    });
       
    $('#customer, #city').on('keyup', function()
    {
        search();
    });
    $('#systemType').on('change', function()
    {
        search();
    });
    $('#customer-search-form').submit(function(e)
    {
        e.preventDefault();
        search();
    });
</script>
