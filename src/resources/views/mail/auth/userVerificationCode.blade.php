<x-mail::message>
# Hello {{ $user->full_name }}

For security reasons, please enter the below verification code to complete your
two-factor authentication and sign into the {{ config('app.name') }}.

<p style="text-align:center">Verification Code: <strong>{{ $code->code }}<strong></p>

**Note:** This code is valid for 15 minutes.
</x-mail::message>
