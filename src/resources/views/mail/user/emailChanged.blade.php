<x-mail::message>
# Hello {{ $user->full_name }},

Your email address was recently changed to {{ $user->email }}.

If this was not done by you, please contact your System Administrator to 
regain access to your account.

If this was you, you can safely ignore this email.
</x-mail::message>
