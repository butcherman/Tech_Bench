<x-mail::message>
# Hello {{ $user->full_name }},

Tech Tip - {{ $techTip->subject }} has been updated with new information.

<x-mail::button :url="route('tech-tips.show', $techTip->slug)">
Click to View the Tech Tip
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
