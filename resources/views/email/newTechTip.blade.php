@component('mail::message')

@component('mail::panel')
# New Tech Tip
@endcomponent

# {{ $tip->subject }}

### ID:  {{ $tip->tip_id }} | Author: {{ $tip->user->first_name.' '.$tip->user->last_name }}

{!! $tip->description !!}

@component('mail::button', ['url' => route('tip.details', ['id' => $tip->tip_id, 'name' => urlencode($tip->subject)])])
View Tech Tip and Attached Files
@endcomponent


@endcomponent
