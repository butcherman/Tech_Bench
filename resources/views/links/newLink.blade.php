@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 grid-margin">
        <h4>Create New File Link</h4>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <new-file-link-form
                    expire_date="{{\Carbon\Carbon::now()->addDays(30)->format('Y-m-d')}}"
                >
                </new-file-link-form>
            </div>
        </div>
    </div>
</div>
@endsection
