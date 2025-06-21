<x-mail::message>
# Hello {{ $user->full_name }},

A Tech Tip Comment was recently flagged for review.

Tech Tip Subject - {{ $comment->TechTip->subject }}.

Comment - {{ $comment->comment }}

<x-mail::button :url="route('admin.tech-tips.flagged-comments.index')">
Click to View the Comment
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
