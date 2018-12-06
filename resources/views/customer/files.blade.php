@if(!$files->isEmpty())
    @foreach($files as $file)
        <tr>
            <td>
                <a href="{{route('downloadPage', ['id' => $file->file_id, 'filename' => $file->file_name])}}">{{$file->name}}</a>
            </td>
            <td>
                {{$file->description}}
            </td>
            <td class="d-none d-sm-table-cell">
                {{$file->first_name}} {{$file->last_name}}
            </td>
            <td>
                {{date('M d, Y', strtotime($file->created_at))}}
            </td>
            <td class="d-none d-sm-table-cell">
                <a href="#edit-modal" title="Edit File Data" class="edit-file" data-toggle="modal" data-tooltip="tooltip" data-file="{{$file->cust_file_id}}"><i class="fa fa-pencil text-muted" aria-hidden="true"></i>
                </a>
                <a href="#edit-modal" title="Delete File" class="delete-file" data-toggle="modal" data-tooltip="tooltip" data-file="{{$file->cust_file_id}}"><i class="fa fa-trash text-muted" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center"><h6>No Files</h6></td>
    </tr>
@endif
