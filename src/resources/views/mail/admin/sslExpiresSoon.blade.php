<x-mail::message>
# Hello,

The SSL Certificate will expire in {{ $daysLeft }} days.

In order to have proper secure communication with the {{ config('app.name') }} server, 
you must upload a valid SSL Certificate.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
