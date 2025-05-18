<x-mail::message>
# Hello {{ $user->full_name }},

A Tech Tip Comment was recently flagged for review.

Tech Tip Subject - {{ $comment->TechTip->subject }}.

Comment - {{ $comment->comment }}

<x-mail::button :url="route('tech-tips.comments.index', $comment->TechTip->slug)">
Click to View the Comment
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
