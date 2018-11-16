<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @foreach($sysData as $key => $sys)
                <li class="nav-item">
                    @if($loop->first)
                            <a class="nav-link text-muted active" id="{{str_replace(' ', '-', $key)}}-tab" data-toggle="tab" data-sys="{{$sys[0]->cust_sys_id}}" href="#{{str_replace(' ', '-', $key)}}" role="tab" aria-controls="{{$key}}" aria-selected="true">{{$key}}</a>
                    @else
                        <a class="nav-link text-muted" id="{{str_replace(' ', '-', $key)}}-tab" data-toggle="tab" data-sys="{{$sys[0]->cust_sys_id}}" href="#{{str_replace(' ', '-', $key)}}" role="tab" aria-controls="{{$key}}" aria-selected="false">{{$key}}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    <div class="card-boy tab-content">
        @foreach($sysData as $key => $sys)
            @if($loop->first)
                <div class="tab-pane fade show active" id="{{str_replace(' ', '-', $key)}}" data-type="{{str_replace(' ', '-', $key)}}" role="tabpanel" aria-labelledby="{{$key}}-tab">
                    <dl class="row justify-content-center">
                        <dt class="col-sm-6 text-right">System Type:</dt>
                        <dd class="col-sm-6">{{$key}}</dd>
                        @foreach($sys as $s)
                            <dt class="col-sm-6 text-right">{{$s->name}}:</dt>
                            <dd class="col-sm-6">{{$s->value}}</dd>
                        @endforeach
                    </dl>
                </div>
            @else
                <div class="tab-pane fade show" id="{{str_replace(' ', '-', $key)}}" data-type="{{str_replace(' ', '-', $key)}}" role="tabpanel" aria-labelledby="{{$key}}-tab">
                    <dl class="row justify-content-center">
                        <dt class="col-sm-6 text-right">System Type:</dt>
                        <dd class="col-sm-6">{{$key}}</dd>
                        @foreach($sys as $s)
                            <dt class="col-sm-6 text-right">{{$s->name}}:</dt>
                            <dd class="col-sm-6">{{$s->value}}</dd>
                        @endforeach
                    </dl>
                </div>
            @endif
        @endforeach
    </div>
</div>
<div class="row justify-content-center pad-top">
    <div class="col-4">
        <a href="#edit-modal" class="add-system btn btn-info btn-block" title="Add New System" data-toggle="modal" data-tooltip="tooltip">Add System</a>
    </div>
    <div class="col-4">
        <a href="#edit-modal" class="edit-system btn btn-info btn-block" title="Edit System" data-toggle="modal" data-tooltip="tooltip" data-sysid="0" id="edit-system">Edit System</a>
    </div>
</div>
