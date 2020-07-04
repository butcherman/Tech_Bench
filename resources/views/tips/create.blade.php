@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Create New Tech Tip</h4>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <new-tech-tip-form
                    :tip_types='@json($types)'
                    :equip_types='@json($equip)'
                ></new-tech-tip-form>
            </div>
        </div>
    </div>
</div>
@endsection
