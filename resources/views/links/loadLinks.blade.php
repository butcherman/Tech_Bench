@if(!$links->isEmpty())
    @foreach($links as $link)
        <tr{{$link->expire < date('Y-m-d') ? ' class=table-danger' : ''}}>
            <td class="text-center"><input type="checkbox" class="check-link" value="{{$link->link_id}}" /></td>
            <td><a href="{{route('links.info', ['id' => $link->link_id, 'name' => urlencode($link->link_name)])}}">{{$link->link_name}}</a></td>
            <td>{{$link->file_link_files_count}}</td>
            <td>{{date('M d, Y', strtotime($link->expire))}}</td>
            <td>
<!--                <a href="#" title="Share Link" data-toggle="modal" data-tooltip="tooltip" data-id="{{$link->link_id}}" class="text-muted share-link"><i class="fa fa-share" aria-hidden="true"></i></a>-->
                <a href="#edit-modal" title="Edit Link" data-toggle="modal" data-tooltip="tooltip" data-id="{{$link->link_id}}" class="text-muted edit-link"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
<!--                <a href="#" title="Disable Link" data-toggle="modal" data-tooltip="tooltip" data-id="{{$link->link_id}}" class="text-muted disable-link"><i class="fa fa-ban" aria-hidden="true"></i></a>-->
                <a href="#edit-modal" title="Remove Link" data-toggle="modal" data-tooltip="tooltip" data-id="{{$link->link_id}}" class="text-muted remove-link"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">No File Links</td>
    </tr>
@endif
            