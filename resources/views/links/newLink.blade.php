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
            <new-file-link-form expire_date="{{\Carbon\Carbon::now()->addDays(30)->format('Y-m-d')}}"><img src="{{asset('img/loading.svg')}}" alt="Loading..." class="d-block mx-auto"></new-file-link-form>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-8"> --}}
        {{-- <new-file-link class="pad-bottom"
            submit_route="{{route('links.data.store')}}"
            search_route="{{route('customer.searchID', ':id')}}"
            detail_route="{{route('links.details', [':id', ':name'])}}"
            expire_date="{{date('Y-m-d', strtotime('+30 days'))}}"
        ></new-file-link> --}}
    {{-- </div> --}}
</div>
@endsection
