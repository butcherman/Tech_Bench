<dl class="row justify-content-center">
    <dt class="col-sm-6 text-right">System Type:</dt>
    <dd class="col-sm-6" id="edit-system-name" data-sysname="{{$sysName}}">{{$sysName}}</dd>
    @foreach($sysData as $sys)
        <dt class="col-sm-6 text-right">{{$sys->name}}:</dt>
        @if(!$sys->hidden)
            <dd class="col-sm-6">{{$sys->value}}</dd>
        @else
            <dd class="col-sm-6 password-field">
                <span class="password-hidden">********</span>
                <span class="password-shown">{{$sys->value}}</span>
            </dd>
        @endif
    @endforeach
    <span>** Hover to View Hidden Fields **</span>
</dl>
<div class="row justify-content-center">
    <div class="col-4">
        <a href="#edit-modal" class="add-system btn btn-info btn-block" title="Add New System" data-toggle="modal" data-tooltip="tooltip">Add System</a>
    </div>
    <div class="col-4">
        <a href="#edit-modal" class="edit-system btn btn-info btn-block" title="Edit System" data-toggle="modal" data-sysid="{{$sysID}}" data-tooltip="tooltip">Edit System</a>
    </div>
</div>
