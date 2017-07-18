<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-center">Tech Tips</h1>
    </div>
    <div>
        <form id="tip-search-form">
            <div class="form-group row">
                <label for="keyword" class="col-md-3 text-right">Search Tech Tips By:</label>
                <div class="col-md-3">
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword Or ID Number" />
                </div>
                <div class="col-md-3">
                    <select name="systemType" id="systemType" class="form-control">
                        <option value="">Select System Type</option>
                        <?= $data['optList']; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="form-control"><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="" id="search-tech-tips">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr id="pager" class="form-horizontal">
                    
                    <td colspan="2" class="text-center">
                        <button type="button" class="btn first"><span class="glyphicon glyphicon-step-backward"></span></button>
                        <button type="button" class="btn prev"><span class="glyphicon glyphicon-backward"></span></button>
                        <span class="pagedisplay"></span>
                        <button type="button" class="btn next"><span class="glyphicon glyphicon-forward"></span></button>
                        <button type="button" class="btn last"><span class="glyphicon glyphicon-step-forward"></span></button>
                        <select class="pagesize input-mini" title="Select page size">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                        </select>   
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td colspan="2" class="text-center">Search Tech Tips</td>
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
        $.post('/tips/searchTips', $('#tip-search-form').serialize())
         .done(function(data)
         {
            $('#search-tech-tips > tbody').html(data);
            
            $('#search-tech-tips').trigger('update');
            var rowCount = $('#search-tech-tips tr').length;            
        });
    }
    
    $('#search-tech-tips').tablesorter(
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
    
    $('#keyword').on('keyup', function()
    {
        search();
    });
    
    $('#systemType').on('change', function()
    {
        search();
    });
    
    $('#tip-search-form').submit(function(e)
    {
        e.preventDefault();
        search();
    });
    
    search();
</script>