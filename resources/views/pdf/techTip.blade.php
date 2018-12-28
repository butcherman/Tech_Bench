<!DOCTYPE html>
<html>
<head>
	<title>Tech Tip - {{$data->subject}}</title>
    <link href="{{ asset('css/pdfDownload.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
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
            <span class="d-block d-sm-inline"><strong>Author: </strong>{{$data->user->first_name}} {{$data->user->last_name}}</span> |
            <span class="d-block d-sm-inline"><strong>Date: </strong>{{date('M j, Y', strtotime($data->created_at))}}</span> |
            @if($data->created_at != $data->updated_at)
                <span class="d-block d-sm-inline"><strong>Updated: </strong>{{date('M j, Y', strtotime($data->updated_at))}}</span> |
            @endif
            <span class="d-block d-sm-inline">
                <strong>Tags: </strong> <span></span>
                @foreach($systems as $sys)
                    <div class="tech-tip-tag">{{$sys->name}}</div>
                @endforeach
            </span>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10" id="tech-tip-information">
            {!!$data->description!!}
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8" id="tech-tip-comments">
            <h4>Comments</h4>
            <div>
                @if(!$comments->isEmpty())
                    <ul class="list-group">
                        @foreach($comments as $comment)
                            <li class="list-group-item">
                                {{$comment->comment}} <span class="comment-author d-block d-sm-inline">- {{$comment->first_name}} {{$comment->last_name}} - {{date('M d, Y', strtotime($comment->created_at))}}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center">No Comments</div>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>