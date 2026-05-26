@extends('pdf.template')

@section('title', 'Tech Tip')

@section('content')
    <div class="text-faded">
        <div class="float-start">
            <strong>ID: </strong>
            {{ $tipData->tip_id }}
        </div>
        <div class="float-end">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <strong>Created:</strong>
                        </td>
                        <td>
                            {{ Illuminate\Support\Carbon::parse($tipData->created_at)->toFormattedDateString() }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Updated:</strong>
                        </td>
                        @if ($tipData->updated_id)
                            <td>
                                {{ Illuminate\Support\Carbon::parse($tipData->updated_at)->toFormattedDateString() }}
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="clear-fix light-border-bottom">
            <strong>For Equipment:</strong>
            @foreach ($tipData->Equipment as $equip)
                {{ $equip->name }}
                @if(!$loop->last)
                    |
                @endif
            @endforeach
        </div>
    </div>
    <h2 class="text-center">{{ $tipData->subject }}</h2>
    <div style="margin-bottom: 5em">
        {!! $tipData->details !!}
    </div>
    @if(count($tipData->Files) > 0)
        <div class="light-border-top">
            <h3 class="text-center">Attachments:</h3>
            <div class="center three-quarter-width">
                <ul style="list-style: none;">
                    @foreach ($tipData->Files as $fileUpload)
                        <li class="text-center">
                            <a href="{{ route('download', [$fileUpload->file_id, $fileUpload->file_name]) }}">
                                {{ $fileUpload->file_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection