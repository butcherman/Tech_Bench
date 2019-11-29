@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>Create New Tech Tip</h4>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <new-tip-form
                    :tip_types="{{json_encode($tipTypes)}}"
                    :sys_types="{{json_encode($sysTypes)}}"
                ></new-tip-form>
            </div>
        </div>
    </div>
</div>
<div class="clearfix pad-bottom">&nbsp;</div>
@endsection
