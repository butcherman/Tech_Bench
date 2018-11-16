@if(!$contacts->isEmpty())    
    @foreach($contacts as $cont)
        <tr>
            <td>{{$cont->name}}</td>
            <td>
                @if(!empty($cont->CustomerContactsView))
                    @foreach($cont->CustomerContactsView as $num)
                        <i class="fa {{$num->icon_class}}" aria-hidden="true"></i> {{$num->description}} - 
                        @if(!empty($num->extension))
                            <a href="tel:{{$num->phone_number}},{{$num->extension}}" title="Call {{$num->description}}" data-tooltip="tooltip">
                            {{preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $num->phone_number)}} Ext {{$num->extension}}</a><br />
                        @else
                            <a href="tel:{{$num->phone_number}}" title="Call {{$num->description}}" data-tooltip="tooltip">
                            {{preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $num->phone_number)}}</a><br />
                        @endif
                    @endforeach
                @endif
            </td>
            <td><a href="mailto:{{$cont->email}}">{{$cont->email}}</a></td>
            <td data-contact="{{$cont->cont_id}}">
                <a href="#edit-modal" class="text-muted edit-contact" title="Edit Contact" data-toggle="modal" data-tooltip="tooltip"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <a href="#edit-modal" class="text-muted delete-contact" title="Delete Contact" data-toggle="modal" data-tooltip="tooltip"><i class="fa fa-trash" aria-hidden="true"></i></a>
                <a href="{{route('customer.vcard', ['id' => $cont->cont_id])}}" class="text-muted download-vcard" title="Download VCard" data-tooltip="tooltip"><i class="fa fa-address-card" aria-hidden="true"></i></a>

            </td>
        </tr>
    @endforeach
@else
    <tr><td colspan="4" class="text-center">No Contacts</td></tr>
@endif
