@extends('pdf.template')

@section('title', 'Customer Note')

@section('content')
    <h3>
        {{ $customer->name }}
    </h3>
    @if ($customer->dba_name)
        <h5>AKA - {{ $customer->dba_name }}</h5>
    @endif
    <hr style="border-bottom: 1px solid #585555" />
    <h5 class="text-center">{{ $note->subject }}</h5>
    <hr style="border-bottom: 1px solid #585555" />
    <div class="row">
        <div class="col">
            <div class="note-details">{!! $note->details !!}</div>

        </div>
    </div>
    <hr style="border-bottom: 1px solid #585555" />
    <div class="text-muted">
        @if ($note->EquipmentType)
            <div>
                Equipment: {{ $note->CustomerEquipment->equip_name }}
            </div>
        @endif
        <div>
            Created: {{ date('M d, Y', strtotime($note->created_at)) }} by
            {{ $note->author }}
        </div>
        @if ($note->updated_author)
            <div>
                Updated: {{ date('M d, Y', strtotime($note->updated_at)) }} by
                {{ $note->updated_author }}
            </div>
        @endif
    </div>
@endsection
