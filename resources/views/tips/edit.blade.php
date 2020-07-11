@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Edit Tech Tip</h4>
    </div>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <edit-tip-form
                    :tip_types='@json($types)'
                    :equip_types='@json($equip)'
                    :details='@json($details)'
                ></edit-tip-form>
            </div>
        </div>
    </div>
</div>
@endsection
