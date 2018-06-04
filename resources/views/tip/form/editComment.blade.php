<div class="row justify-content-center">
    <div class="col-8">
        {!! Form::model($comment, ['route' => ['tip.comments.update', $commentID], 'id' => 'edit-comment-form']) !!}
            @method('PUT')
            {{ Form::bsTextarea('comment', 'Edit Comment', null, ['rows' => '5']) }}
            {{ Form::bsSubmit('Edit Comment') }}
        {!! Form::close() !!}
    </div>
</div>
