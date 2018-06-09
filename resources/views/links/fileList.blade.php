<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>File Name:</th>
                <th>Date Added:</th>
                <th>Actions:</th>
            </tr>
        </thead>
        <tbody>
            @if(!$files->isEmpty())
                @foreach($files as $file)
                    <tr>
                        <td><a href="{{route('downloadPage', ['id' => $file->file_id, 'name' => $file->file_name])}}">{{$file->file_name}}</a></td>
                        <td>{{$file->created_at}}</td>
                        <td><a href="#edit-modal" title="Remove File" data-toggle="modal" data-tooltip="tooltip" data-id="{{$file->link_file_id}}" class="text-muted remove-file"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center">No Files</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>