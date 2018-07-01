@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>System Administration</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>System Settings</strong></div>
                <div class="card-body"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Categories and Systems</strong></div>
                <div class="card-body">
                    @if(!empty($sysArr))
                        @foreach($sysArr as $cat => $sys)
                            <ul class="list-group">
                                <li class="list-group-item text-center">
                                    <a href="#collapse_{{str_replace(' ', '-', $cat)}}" class="category-button btn btn-link btn-block" data-toggle="collapse">{{strtoupper($cat)}}</a>
                                    <ul class="list-group collapse" id="collapse_{{str_replace(' ', '-', $cat)}}">
                                        @if(!empty($sys))
                                            @foreach($sys as $s)
                                                <li class="list-group-item text-left"><a href="{{route('installer.editSystem', urlencode($s))}}" class="btn btn-info btn-block">{{$s}}</a></li>
                                            @endforeach
                                        @endif
                                        <li class="list-group-item text-left">
                                            <a href="{{route('installer.newSys', urlencode($cat))}}" class="btn btn-primary btn-block">Add System</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        @endforeach
                    @endif
                    <li class="list-group-item text-left">
                        <a href="{{route('installer.newCat')}}" class="btn btn-primary btn-block">Add Category</a>
                    </li>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
