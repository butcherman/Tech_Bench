@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-10 grid-margin">
        <h3>
            <tech-tip-bookmark
                :is_fav='@json($isFav)'
                :tip_id='@json($details->tip_id)'
            ></tech-tip-bookmark>
            {{$details->subject}}
        </h3>
        <div class="tip-details">
            <span class="d-block d-sm-inline-block"><strong>ID:</strong>  {{$details->tip_id}}</span>
            <span class="d-block d-sm-inline-block"><strong>Created:</strong> {{$details->created_at}}</span>
            <span class="d-block d-sm-inline-block"><strong>Updated:</strong> {{$details->updated_at}}</span>
        </div>
    </div>
    <div class="col-sm-2">
        <b-button variant="info" block pill size="sm" href="{{route('tips.download', $details->tip_id)}}">
            <i class="fas fa-download"></i>
            Download Tip
        </b-button>
        @can('hasAccess', 'Edit Tech Tip')
        <b-button variant="warning" block pill size="sm" href="{{route('tips.edit', $details->tip_id)}}">
            <i class="far fa-edit"></i>
            Edit Tip
        </b-button>
        @endcan
    </div>
</div>
<div class="row">
    <div class="col-12 tip-equipment grid-margin">
        <div><strong>For Equipment:</strong></div>
        @foreach ($details->SystemTypes as $type)
        <b-badge pill variant="info" class="ml-1 mb-1">{{$type->name}}</b-badge>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-12 stretch-card grid-margin">
        <div class="card rounded">
            <div class="card-body tip-description">
                <div class="card-title border-bottom">Details:</div>
                <div>
                    {!! $details->description !!}
                </div>
            </div>
        </div>
    </div>
</div>
@if(count($details->TechTipFiles) > 0)
<div class="row">
    <div class="col stretch-card grid-margin">
        <div class="card rounded">
            <div class="card-body">
                <div class="card-title border-bottom">
                    Attachments:
                </div>
                <ul class="pl-5">
                    @foreach ($details->TechTipFiles as $file)
                    <li>
                        <a href="{{route('download', [$file->file_id, $file->files->file_name])}}">{{$file->files->file_name}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-10 stretch-card grid-margin">
        <div class="card rounded">
            <div class="card-body">
                <div class="card-title border-bottom">
                    Discussion:
                </div>
                <tech-tip-comments :tip_id='@json($details->tip_id)'></tech-tip-comments>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
