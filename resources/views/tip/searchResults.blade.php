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
        @if(!$results->isEmpty())
            @foreach($results as $res)
                <tr>
                    <td><a href="{{route('tip.details', ['id' => $res->tip_id, 'sub' => urlencode($res->subject)])}}">{{$res->subject}}</a></td>
                    <td>{{date('M j, Y', strtotime($res->created_at))}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2" class="text-center">No Tech Tips Found</td>
            </tr>
        @endif
    </tbody>
</table>
