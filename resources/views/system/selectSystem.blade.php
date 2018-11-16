@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Select A System</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($systems as $sys)
                            <li class="list-group-item text-center"><a href="{{ route('system.details', ['cat' => $category, 'sys' => $sys->name]) }}">{{ $sys->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
