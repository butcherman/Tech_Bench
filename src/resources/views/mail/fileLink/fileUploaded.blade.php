<x-mail::message>
# Hello

A file has been uploaded to the File Link - {{ $link->link_name }}.

<x-mail::button :url="route('links.show', $link->link_id)">
	Click to View the File
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
