@if(!$notes->isEmpty())
    <div class="row pad-bottom">
        @foreach($notes as $note)
            <div class="col-md-3 pad-bottom customer-note-card">
                <div class="card">
                    <div class="card-header{{ $note->urgent ? ' bg-danger' : ' bg-info' }}">
                        {{$note->subject}}
                        <a href="{{route('customer.downloadNote', ['id' => $note->note_id])}}" class="float-right text-white" title="Download as PDF" data-tooltip="tooltip"><i class="fa fa-download"></i></a>
                    </div>
                    <div class="card-body">
                        {!! $note->description !!}
                    </div>
                    <div class="card-footer">
                        <a href="#edit-modal" data-note="{{$note->note_id}}" class="btn btn-info btn-block edit-note-button">Edit Note</a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="row pad-bottom justify-content-center">
        <div class="col-10 col-sm-4"><h6 class="text-center">No Notes</h6></div>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-6 col-sm-4">
        <a href="#edit-modal" id="new-note-link" class="btn btn-block btn-info" data-toggle="modal">Add Note</a>
    </div>
</div>
