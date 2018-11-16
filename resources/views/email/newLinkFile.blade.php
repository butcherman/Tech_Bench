@component('mail::message')

@component('mail::panel')
    <h2 style="text-align: center;">New File Uploaded</h2>
@endcomponent

A new file has been uploaded to {{$link->link_name}}.

@component('mail::button', ['url' => route('links.info', ['id' => $link->link_id, 'name' => $link->link_name])])
    View File Link
@endcomponent

@endcomponent
