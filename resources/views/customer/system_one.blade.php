<dl class="row justify-content-center">
    <dt class="col-sm-6 text-right">System Type:</dt>
    <dd class="col-sm-6" id="edit-system-name" data-sysname="{{$sysName}}">{{$sysName}}</dd>
    @foreach($sysData as $sys)
        <dt class="col-sm-6 text-right">{{$sys->name}}:</dt>
        <dd class="col-sm-6">{{$sys->value}}</dd>
    @endforeach
</dl>
<div class="row justify-content-center">
    <div class="col-4">
        <a href="#edit-modal" class="add-system btn btn-info btn-block" title="Add New System" data-toggle="modal" data-tooltip="tooltip">Add System</a>
    </div>
    <div class="col-4">
        <a href="#edit-modal" class="edit-system btn btn-info btn-block" title="Edit System" data-toggle="modal" data-sysid="{{$sysID}}" data-tooltip="tooltip">Edit System</a>
    </div>
</div>
