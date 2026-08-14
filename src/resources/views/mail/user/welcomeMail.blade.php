<x-mail::message>
# Welcome to the {{ config('app.name') }}

Hello {{ $user->full_name }},

A new {{ config('app.name') }} has been created for you.  

Your username to login is: **{{ $user->username }}**

Please click on the link below to set a password and complete the setup 
of your new account.

<x-mail::button :url="route('initialize', $token)">
Setup Account
</x-mail::button>

**Note:** This link is only valid for seven days.
</x-mail::message>
