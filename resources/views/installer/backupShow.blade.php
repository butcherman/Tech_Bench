@if(empty($backups))
    <tr>
        <td colspan="2" class="text-center">No Backups - It Would Be A Good Idea to Create One</td>
    </tr>
@else
    @foreach($backups as $backup)
        <tr>
            <td>{{$backup}}</td>
            <td>
                <a href="{{route('installer.downloadBackup', ['name' => $backup])}}" class="text-muted download-backup" title="Download Backup" data-tooltip="tooltip"><i class="fa fa-download" aria-hidden="true"></i></a>
                <a href="#edit-modal" class="text-muted delete-backup" title="Delete Backup" data-toggle="modal" data-tooltip="tooltip" data-name="{{$backup}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
        </tr>
    @endforeach
@endif
