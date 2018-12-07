@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">File Links</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>File Links Administration</h1></div>
        </div>
    </div>
    @if(session()->has('success'))
        <div class="row justify-content-center">
            <div class="col-8">
                <h2 class="text-center alert alert-success">{{session('success')}}</h2>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Links</th>
                            <th>Expired Links</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center"><strong>Click on a user to see their links</strong></td>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($links as $link)
                            <tr>
                                <td>
                                    <a href="{{route('admin.showLinks', $link->user_id)}}">{{$link->first_name}} {{$link->last_name}}</a>
                                </td>
                                <td>{{$link->FileLinks->count()}}</td>
                                <td>
                                    {!!
                                        $link->FileLinks->filter(function($lnk)
                                        {
                                            if($lnk->expire < date('Y-m-d'))
                                            {
                                                return $lnk;
                                            }                   
                                        })->count();                                             
                                    !!}                                      
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
