@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Select A Category</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($categories as $cat)
                            <li class="list-group-item text-center text-uppercase">
                                <a href="{{ route('system.select', ['cat' => $cat->name]) }}">{{ $cat->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
