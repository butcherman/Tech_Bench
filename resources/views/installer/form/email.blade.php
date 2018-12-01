@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Customize Email Settings</h1></div>
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
            {!!Form::open(['route' => 'installer.submitEmail'])!!}
                {{Form::bsText('host', 'Email Host', config('mail.host'), ['required'])}}
                {{Form::bsText('port', 'Email Port', config('mail.port'), ['required'])}}
                {{Form::bsSelect('encryption', 'Email Encryption Type', ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None'], config('mail.encryption'))}}
                {{Form::bsText('username', 'Email Username', config('mail.username'), ['required'])}}
                {{Form::bsPassword('password', 'Email Password', config('mail.password'))}}
                {{Form::bsSubmit('Update Tech Bench')}}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection
