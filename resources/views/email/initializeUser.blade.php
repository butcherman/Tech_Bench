@component('mail::message')

@component('mail::panel')
<h2 style="text-align: center;">Welcome to the Tech Bench</h2>
@endcomponent

Hello {{$name}}.

A new user account has been created for you.  

Your new username is:  **{{$username}}**

Click the link below to complete the setup.

{{--@component('mail::button', ['url' => route('initialize', $link)])--}}
@component('mail::button', ['url' => route('initialize', $link)])
Setup Account
@endcomponent

@endcomponent
