<x-mail::message>
# Hello {{ $user->full_name }},

A new Tech Tip has been created.

Subject - {{ $techTip->subject }}.

<x-mail::button :url="route('tech-tips.show', $techTip->slug)">
Click to View the Tech Tip
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
