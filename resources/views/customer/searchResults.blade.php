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
        @if(!$results->isEmpty())
            @foreach($results as $res)
                <tr>
                    <td><a href="{{route('customer.details', ['id' => $res->cust_id, 'name' => urlencode($res->name)])}}">{{$res->name}}</a></td>
                    <td>{{$res->city}}, {{$res->state}}</td>
                    <td>
                        @if(!$res->CustomerSystems->isEmpty())
                            @foreach($res->CustomerSystems as $sys)
                                {{$sys->SystemTypes->name}}<br />
                            @endforeach
                        @else
                            None
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" class="text-center">No Customer Data</td>
            </tr>
        @endif
    </tbody>
</table>
