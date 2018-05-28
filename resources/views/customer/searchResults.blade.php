@if(!$results->isEmpty())
    @foreach($results as $res)
        <tr>
            <td><a href="{{route('customer.details', ['id' => $res->cust_id, 'name' => urlencode($res->name)])}}">{{$res->name}}</a></td>
            <td>{{$res->city}}, {{$res->state}}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center">No Customer Data</td>
    </tr>
@endif
