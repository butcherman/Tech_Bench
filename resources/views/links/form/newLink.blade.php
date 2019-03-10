@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>New File Link</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <new-file-link class="pad-bottom" 
                submit_route="{{route('links.data.store')}}" 
                search_route="{{route('customer.searchID', ':id')}}"
                detail_route="{{route('links.details', [':id', ':name'])}}"
                expire_date="{{date('Y-m-d', strtotime('+30 days'))}}" 
            ></new-file-link>
        </div>
    </div>
</div>
@endsection
