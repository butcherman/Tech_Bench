@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tip.index')}}">Tech Tips</a></li>
    <li class="breadcrumb-item active">Tip Details</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <h2>
            <i class="fa fa-bookmark {{is_null($isFav) ? 'bookmark-unchecked' : 'bookmark-checked'}}" aria-hidden="true" title="Bookmark Tech Tip" data-tooltip="tooltip"></i> 
            {{$data->subject}}            
        </h2>
        <div class="col-12 tech-tip-details">
            @if($current_user->hasAnyRole(['installer', 'admin']) || $data->user_id == $current_user->user_id)
                <a href="{{route('tip.id.edit', ['id' => $data->tip_id])}}" title="Edit This Tip" data-tooltip="tooltip" class="d-block d-sm-inline"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            @endif
            <span class="d-block d-sm-inline"><strong>ID#: </strong>{{$data->tip_id}}</span>
            <span class="d-block d-sm-inline"><strong>Author: </strong>{{$data->user->first_name}} {{$data->user->last_name}}</span>
            <span class="d-block d-sm-inline"><strong>Date: </strong>{{date('M j, Y', strtotime($data->created_at))}}</span>
            @if($data->created_at != $data->updated_at)
                <span class="d-block d-sm-inline"><strong>Updated: </strong>{{date('M j, Y', strtotime($data->updated_at))}}</span>
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
        <div class="col-12 col-sm-8" id="tech-tip-attachements">
            <h4>Attachements:</h4>
            @if(!$files->isEmpty())
                <ul class="list-group">
                    @foreach($files as $file)
                        <li class="list-group-item"><a href="{{route('downloadPage', ['id' => $file->file_id, 'filename' => $file->file_name])}}">{{$file->file_name}}</a></li>
                    @endforeach
                </ul>
            @else
                <p class="text-center">No Attachements</p>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8" id="tech-tip-comments">
            <h4>Comments</h4>
            <div>
                <p class="text-center"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</p>
            </div>
        </div>
    </div>
</div>
@include('_inc/modal')
@endsection

@section('script')
<script>
    var tipID = {{$data->tip_id}}
    loadComments();
    //  Add/Remove customer bookmark
    $('.fa-bookmark').on('click', function()
    {
        var url = '{{route('tip.toggleFav', ['id' => $data->tip_id, 'action' => ':act'])}}';
        if($(this).hasClass('bookmark-unchecked'))
        {
            url = url.replace(':act', 'add');
            $.get(url);
            $(this).removeClass('bookmark-unchecked');
            $(this).addClass('bookmark-checked');
        }
        else
        {
            url = url.replace(':act', 'remove');
            $.get(url);
            $(this).removeClass('bookmark-checked');
            $(this).addClass('bookmark-unchecked');
        }
    });
    //  Load the edit comment form
    $('#tech-tip-comments').on('click', '.edit-comment', function()
    {
        var url = '{{route('tip.comments.edit', ['id' => ':id'])}}';
        url = url.replace(':id', $(this).data('id'));
        $('#edit-modal').find('.modal-title').text('Edit Comment');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#edit-comment-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    resetEditModal();
                    loadComments();
                });
            });
        });
    });
    //  Delete a comment
    $('#tech-tip-comments').on('click', '.remove-comment', function()
    {
        var url = '{{route('tip.comments.destroy', ['id' => ':id'])}}';
        url = url.replace(':id', $(this).data('id'));
        $('#edit-modal').find('.modal-title').text('Delete Comment');
        $('#edit-modal').find('.modal-body').load('{{route('confirm')}}', function()
        {
            $('.select-yes').on('click', function()
            {
                $.ajax(
                {
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res)
                    {
                        resetEditModal();
                        loadComments();
                    }
                });
            });
        });
    });
    //  Function to load comments
    function loadComments()
    {
        var url = '{{route('tip.comments.show', ['id' => ':id'])}}';
        url = url.replace(':id', tipID);
        $('#tech-tip-comments > div').load(url, function()
        {
            $('[data-tooltip="tooltip"]').tooltip();
            $('#tipID').val(tipID);
            $('#tech-tip-comment-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function()
                {
                    loadComments();
                });
            });
        });
    }
</script>
@endsection
