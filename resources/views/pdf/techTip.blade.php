@extends('pdf.template')

@section('content')
<div class="row justify-content-center">
    <div class="col-10">
        <h2>
            {{$data->subject}}
        </h2>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-10 tech-tip-details">
        <span class="d-block d-sm-inline"><strong>ID#: </strong>{{$data->tip_id}}</span> |
        <span class="d-block d-sm-inline"><strong>Date: </strong>{{date('M j, Y', strtotime($data->created_at))}}</span> |
        @if($data->created_at != $data->updated_at)
            <span class="d-block d-sm-inline"><strong>Updated: </strong>{{date('M j, Y', strtotime($data->updated_at))}}</span> |
        @endif
        <span class="d-block d-sm-inline">
            <strong>Tags: </strong> <span></span>
            @foreach($data->SystemTypes as $sys)
                <div class="tech-tip-tag">{{$sys->name}}</div>
            @endforeach
        </span>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10" id="tech-tip-information">
        {!! str_replace('/storage/img/', public_path().'/storage/img/', $data->description) !!}
    </div>
</div>
@endsection
