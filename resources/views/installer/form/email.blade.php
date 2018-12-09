@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('installer.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">Email Settings</li>
</ol>
@endsection

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
    <div class="row justify-content-center pad-top">
        <div class="col-md-8">
            <div class="alert alert-danger d-none" id="failed-test"><h5></h5></div>
            <div class="alert alert-success d-none" id="successful-test"><h5 class="text-center">Email Sent Successfully</h5></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            {!!Form::open(['route' => 'installer.submitEmail', 'id' => 'email-settings-form'])!!}
                {{Form::bsText('host', 'Email Host', config('mail.host'), ['required'])}}
                {{Form::bsText('port', 'Email Port', config('mail.port'), ['required'])}}
                {{Form::bsSelect('encryption', 'Email Encryption Type', ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None'], config('mail.encryption'))}}
                {{Form::bsText('username', 'Email Username', config('mail.username'), ['required'])}}
                {{Form::bsPassword('password', 'Email Password', config('mail.password'))}}
                <button type="button" id="send-test-email" class="btn btn-block btn-warning">Send Test Email</button>
                {{Form::bsSubmit('Update Tech Bench')}}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#send-test-email').on('click', function()
    {
        $('#successful-test').addClass('d-none');
        $('#failed-test').addClass('d-none');
        $.post('{{route('installer.testEmail')}}', $('#email-settings-form').serialize(), function(res)
        {
            if(res === 'success')
            {
                $('#successful-test').removeClass('d-none');
            }
            else
            {
                $('#failed-test').removeClass('d-none');
                $('#failed-test').find('h5').html('There was an error sending the message<br />Check the logs for more specific information');
            }
        }).fail(function()
        {
            $('#failed-test').removeClass('d-none');
            $('#failed-test').find('h5').html('There was an error sending the message<br />Check the logs for more specific information');
        });
    });
</script>
@endsection
