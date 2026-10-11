<x-mail::message>
# Hello,

The Azure Secret for Single Sign On will expire in {{ $daysLeft }} days.

To avoid interruptions in Office 365 Signe Sign On, generate a new secret 
before the certificate expires.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
