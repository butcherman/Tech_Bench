@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>{{env('APP_NAME')}}</h1></div>
        </div>
    </div>
    <div class="jumbotron">
        @if(!$files->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>You Have Files Available For Download</h1></div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>File:</th>
                                    <th>Date Added:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td><a href="{{route('downloadPage', ['id' => $file->file_id, 'name' => $file->file_name])}}">{{$file->file_name}}</a></td>
                                        <td>{{$file->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        @if($allowUp)
            <div class="alert-success"><h2 class="text-center"></h2></div>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Upload Files</h1></div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                     {!! Form::open(['route' => ['userLink.upload', $details->link_hash], 'id' => 'new-file-form', 'enctype' => 'multipart/form-data', 'files' => true]) !!}
                        {{ Form::bsText('name', 'Your Name', null, ['required'])}}
                        @include('_inc.dropMultiFile')
                        {{ Form::bsTextarea('note', 'Note')}}
                        {{ Form::bsSubmit('Upload Files')}}
                    {!! Form::close() !!}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function()
{
    tinymce.init(
    {
        selector: 'textarea',
        height: '200',
        plugins: 'autolink table'
    });
    multiFileDrop($('#new-file-form'));
});
    
function uploadComplete(res)
{
    $('.alert-success').find('h2').text('Upload Successful');
    var name = $('#name').val();
    $('#new-file-form')[0].reset();
    $('#name').val(name);
    $('.submit-button').attr('disabled', false);
    resetProgressBar();
}
function uploadFailed(res)
{
    var msg = 'There was a problem adding the File Tip.\nPlease contact the system administrator';
}
</script>
@endsection
