@extends('pdf.template')

@section('title', 'Tech Tip')

@section('content')
<h2>{{ $tipData->subject }}</h2>
<div class="tip-details" style="color: #999999; border-bottom: 1px solid #999999;">
    <span>
        <strong>ID: </strong>
        {{ $tipData->tip_id }}
    </span>
    <span>|</span>
    <span>
        <strong>Created: </strong>
        {{ $tipData->created_at }}
    </span>
    @if($tipData->updated_id)
        <span>|</span>
        <span>
            <strong>Last Updated: </strong>
            {{ $tipData->updated_at }}
        </span>
    @endif 
    <div style="border-top: 1px solid #999999;">
        <div style="margin-bottom: 0.5em">For Equipment:</div>
        @foreach ($tipData->EquipmentType as $equip)
            <span>
                {{$equip->name}}
            </span>
            <span>|</span>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="mt-4">
            {!! $tipData->details !!}
        </div>
    </div>
</div>
@if($tipData->FileUpload)
    <div class="row">
        <div class="col">
            <h3>Attachments:</h3>
            <ul class="list-group">
                @foreach($tipData->FileUpload as $fileUpload)
                    <li class="list-group-item">
                        <a href="{{route('download', [$fileUpload->file_id, $fileUpload->file_name])}}">
                            {{$fileUpload->file_name}}
                        </a>
                    </li>
                @endforeach 
            </ul>
        </div>
    </div>
@endif 

@endsection