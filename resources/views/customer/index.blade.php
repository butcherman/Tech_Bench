@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Customers</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customers</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('customer.form.search')
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="table-responsive" id="customer-results-table">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>City, State</th>
                        <th>System Type</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-center">
                            <a href="{{route('customer.id.create')}}" title="New Customer" class="btn btn-primary" data-toggle="tooltip"><i class="fa fa-plus" aria-hidden="true"></i> Add Customer</a>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</td>
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
        searchCustomer();

        $('#search-customer-form').on('submit', function(e)
        {
            e.preventDefault();
            table.destroy();
            searchCustomer();
        })

        $('#reset-search-form').on('click', function()
        {
            $('#search-customer-form')[0].reset();
        });

        //  Search customer function
        function searchCustomer()
        {
            $.post('{{Route('customer.search')}}', $('#search-customer-form').serialize())
             .done(function(data)
            {
                $('#customer-results-table').html(data);
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

        //  Filter the "Customer Name"
        $("#search-name").on("keyup", function()
        {
            searchCustomer();
        });

        //  Filter the "City"
        $("#search-city").on("keyup", function()
        {
            searchCustomer();
        });

        //  Fileter System Type
        $('#search-system').on('change', function()
        {
            searchCustomer();
        });
    });
</script>
@endsection
