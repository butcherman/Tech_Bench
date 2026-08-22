<x-mail::message>
# Hello {{ $user->full_name }},

A comment was made on a Tech Tip.

Subject - {{ $comment->TechTip->subject }}.

Comment - {{ $comment->comment }}

<x-mail::button :url="route('tech-tips.show', $comment->TechTip->slug)">
Click to View the Tech Tip
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
