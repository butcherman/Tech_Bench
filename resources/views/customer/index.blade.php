@extends('layouts.app')

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
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>City, State</th>
                        <th>System Type</th>
                        <th>Backup Loaded</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="4">
                        
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</td>
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
        searchCustomer();
        
        $('#search-customer-form').on('submit', function(e)
        {
            e.preventDefault();
            searchCustomer();
        })
        
        //  Search customer function
        function searchCustomer()
        {
            $.post('{{Route('customer.search')}}', $('#search-customer-form').serialize())
                .done(function(data)
                {
                    $('#dataTable > tbody').html(data);
                });
        }
    });
</script>
@endsection
