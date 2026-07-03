@extends('pdf.template')

@section('title', 'Customer Note')

@section('content')
    <h3 class="text-center">
        {{ $customer->name }}
    </h3>
    @if ($customer->dba_name)
        <h5 class="text-center">AKA - {{ $customer->dba_name }}</h5>
    @endif
    <h5 class="text-center">{{ $note->subject }}</h5>
    <div>
        {!! $note->details !!}
    </div>
    <div class="text-faded light-border-top" style="padding: 1em">
        <table>
            <tbody>
                @if ($note->noteType === 'Equipment')
                    <tr>
                        <td>
                            <strong>Equipment: </strong>
                        </td>
                        <td>
                            {{ $note->CustomerEquipment->equip_name }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>
                        <strong>Created:</strong>
                    </td>
                    <td>
                        {{ Illuminate\Support\Carbon::parse($note->created_at)->toFormattedDateString() }}
                        by
                        {{ $note->author     }}
                    </td>
                </tr>
                @if ($note->updated_author)
                    <tr>
                        <td>
                            <strong>Updated:</strong>
                        </td>
                        <td>
                            {{ Illuminate\Support\Carbon::parse($note->updated_at)->toFormattedDateString() }}
                            by
                            {{ $note->updated_author }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection