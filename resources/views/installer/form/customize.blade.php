@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('installer.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Customize Tech Bench</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customize Tech Bench</h1></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session()->has('success'))
                <div class="alert alert-success">{!!session('success')!!}</div>
            @endif
            @if($errors->any())
                @foreach($errors->all() as $err)
                    <div class="alert alert-danger">
                        <h2 class="text-center">{{$err}}</h2>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!!Form::open(['route' => 'installer.submitCustomize'])!!}
                {{Form::bsText('url', 'Website URL', config('app.url'), ['required readonly'])}}
                {{Form::bsTimeZone()}}
                {{Form::bsSubmit('Update Tech Bench')}}
            {!!Form::close()!!}
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-8">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h3>Company Logo</h3></div>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-4">
            <img src="{{config('app.logo')}}" alt="Company Logo" class="img-thumbnail" id="header-logo" />
        </div>
        <div class="col-md-4">

            @include('_inc.drop1File')
            <p class="text-center">Drag new logo in the box above to change</p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#dropzone-box').dropzone(
    {
        url: '{{route('installer.submitLogo')}}',
        uploadMultiple: false,
        maxFiles: 1,
        acceptedFiles: 'image/*',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function()
        {
            var myDrop = this;
            this.on('success', function(files, response)
            {
                if(response === 'success')
                {
                    window.location.replace('{{route('installer.customize')}}');
                }
                else
                {
                    alert('There was an issue processing your request');
                }
            });
        }
    });
</script>
@endsection
