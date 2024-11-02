<x-mail::message>
# Hello {{ $user->full_name }}

This is a test email to verify that your email settings are correct.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
