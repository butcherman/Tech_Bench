@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Tech Tips</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Tech Tips</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('tip.form.search')
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="table-responsive" id="tip-results-table">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <a href="{{route('tip.id.create')}}" title="New Tech Tip" class="btn btn-primary" data-toggle="tooltip"><i class="fa fa-plus" aria-hidden="true"></i> Add New Tech Tip</a>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function()
    {
        var table;
        searchTips();
        
        $('#search-tips-form').on('submit', function(e)
        {
            e.preventDefault();
            table.destroy();
            searchTips();
        })
        
        $('#reset-search-form').on('click', function()
        {
            $('#search-tips-form')[0].reset();
        });
        
        //  Search customer function
        function searchTips()
        {
            $.post('{{Route('tip.search')}}', $('#search-tips-form').serialize())
             .done(function(data)
            {
                $('#tip-results-table').html(data);
                if(!$('#dataTable > tbody').find('td').hasClass('text-center'))
                {                        
                    table = $('#dataTable').DataTable(
                    {
                        'dom': '<"top"i>rt<"row"<"col-4"l><"col-4"p>>',
                        'searching': false
                    }); 
                }
            });
        }
    });
</script>
@endsection
