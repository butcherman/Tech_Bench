<x-mail::message>
# Hello,

This message is to inform you that the SSL Certificate has expired.

This means that communication between the {{ config('app.name') }} and users is not secure.

Please upload a valid SSL Certificate to reinstate secure communications.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
