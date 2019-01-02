<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th></th>
                <th>File Name:</th>
                <th>Date Added:</th>
                <th>Actions:</th>
            </tr>
        </thead>
        <tbody>
            @if(!$files->isEmpty())
                @foreach($files as $file)
                    <tr>
                        @if($type === 'down')
                            <td class="text-center"><input type="checkbox" class="checkbox-file-down" value="{{$file->link_file_id}}" /></td>
                        @elseif($type === 'up')
                            <td class="text-center"><input type="checkbox" class="checkbox-file-up" value="{{$file->link_file_id}}" /></td>
                        @endif
                        <td>
                            <a href="{{route('downloadPage', ['id' => $file->file_id, 'name' => $file->file_name])}}">{{$file->file_name}}</a>
                            @if(!empty($file->FileLinkNotes))
                                <a href="#edit-modal" title="Click to Read Note" data-toggle="modal" data-tooltip="tooltip" class="text-muted read-file-note float-right" data-noteid="{{$file->FileLinkNotes->link_note_id}}"><i class="fa fa-comment" aria-hidden="true"></i></a>
                            @endif
                        </td>
                        <td>{{date('M j, Y', strtotime($file->created_at))}}</td>
                        <td><a href="#edit-modal" title="Remove File" data-toggle="modal" data-tooltip="tooltip" data-id="{{$file->link_file_id}}" class="text-muted remove-file"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No Files</td>
                </tr>
            @endif
        </tbody>
        @if($type === 'down')
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="#edit-modal" data-toggle="modal" class="btn btn-info" id="add-link-file">Add File</a>
                        <a href="#edit-modal" data-toggle="modal" class="btn btn-info" id="delete-multiple-down">Delete Checked</a>
                    </td>
                </tr>
            </tfoot>
        @elseif($type === 'up')
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="#edit-modal" data-toggle="modal" class="btn btn-info" id="delete-multiple-up">Delete Checked</a>
<!--                        <a href="#" id="download-all-files" class="btn btn-info">Download All</a>-->
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
