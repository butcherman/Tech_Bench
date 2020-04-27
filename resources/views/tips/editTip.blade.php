@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>Edit Tech Tip</h4>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <edit-tip-form
                    :tip_types="{{json_encode($tipTypes)}}"
                    :sys_types="{{json_encode($sysTypes)}}"
                    :tip_data="{{json_encode($tipData)}}"
                ></edit-tip-form>
            </div>
        </div>
    </div>
</div>
@endsection
