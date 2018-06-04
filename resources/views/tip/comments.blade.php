@if(!$comments->isEmpty())
    <ul class="list-group">
        @foreach($comments as $comment)
            <li class="list-group-item">
                {{$comment->comment}} - <span class="comment-author">{{$comment->first_name}} {{$comment->last_name}} - {{date('M d, Y', strtotime($comment->created_at))}}</span>
                @if($current_user->hasAnyRole(['installer', 'admin']) || $comment->user_id == $current_user->user_id)
                    <span class="float-right"> 
                        <a href="#edit-modal" title="Edit Comment" data-toggle="modal" data-tooltip="tooltip" data-id="{{$comment->comment_id}}" class="text-muted edit-comment"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                        <a href="#edit-modal" title="Remove Comment" data-toggle="modal" data-tooltip="tooltip" data-id="{{$comment->comment_id}}" class="text-muted remove-comment"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </span>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <div class="text-center">No Comments</div>
@endif

<div class="row justify-content-center">
    <div class="col-8">
        {!! Form::open(['route' => 'tip.comments.store', 'id' => 'tech-tip-comment-form']) !!}
            {{ Form::hidden('tipID', null, ['id' => 'tipID']) }}
            {{ Form::bsTextarea('comment', 'Add Comment', null, ['rows' => '5']) }}
            {{ Form::bsSubmit('Add Comment') }}
        {!! Form::close() !!}
    </div>
</div>
