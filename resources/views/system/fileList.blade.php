@if(!$fileList->isEmpty())
    @foreach($fileList as $file)
        <tr>
            <td><a href="/download/{{$file->file_id}}/{{$file->files->file_name}}" title="{{$file->description}}" data-tooltip="tooltip">{{$file->name}}</a></td>
            <td>{{$file->user->first_name}} {{$file->user->last_name}}</td>
            <td>{{date('M d, Y', strtotime($file->created_at))}}</td>
            <td>
                <a href="#edit-modal" title="Edit File Data" class="edit-file" data-toggle="modal" data-tooltip="tooltip" data-file="{{$file->sys_file_id}}"><i class="fa fa-pencil text-muted" aria-hidden="true"></i>
                </a>
                <a href="#edit-modal" title="Replace File With Newer Version" class="replace-file" data-toggle="modal" data-tooltip="tooltip" data-file="{{$file->sys_file_id}}"><i class="fa fa-retweet text-muted" aria-hidden="true"></i>
                </a>
                <a href="#edit-modal" title="Delete File" class="delete-file" data-toggle="modal" data-tooltip="tooltip" data-file="{{$file->sys_file_id}}"><i class="fa fa-trash text-muted" aria-hidden="true"></i>
                </a>
            
            </td>
        </tr>
    @endforeach
@else
<!--    <tr><td colspan="4">No Files</td></tr>-->
@endif
