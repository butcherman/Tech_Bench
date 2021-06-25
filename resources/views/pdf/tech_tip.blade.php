@extends('pdf.template')

@section('title', 'Tech Tip')

@section('content')
<div class="row">
    <div class="col-sm-10">
        <h3>
            {{$details->subject}}
        </h3>
        <div class="tip-details">
            <span><strong>ID: </strong>{{$details->tip_id}}</span> |
            <span><strong>Created: </strong>{{date('M d, Y', strtotime($details->created_at))}}</span> |
            <span><strong>Last Updated: </strong>{{date('M d, Y', strtotime($details->updated_at))}}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div><strong>For Equipment:</strong></div>
        @foreach ($details->EquipmentType as $equip)
            <span class="badge badge-info">{{$equip->name}}</span>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="mt-4">
            {!! $details->details !!}
        </div>
    </div>
</div>
@if(count($details->FileUploads) > 0)
<div class="row">
    <div class="col">
        <div class="mt-4">
            <h4>Attachments:</h4>
            <div>
                <ul>
                    @foreach ($details->FileUploads as $file)
                    <li>
                        <a href="{{route('download', [$file->file_id, $file->file_name])}}">{{$file->file_name}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <p class="text-center">
                <strong>Note:</strong>
                You must be logged in to download files
            </p>
        </div>
    </div>
</div>
@endif

@endsection
