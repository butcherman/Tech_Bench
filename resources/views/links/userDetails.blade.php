@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>{{config('app.name')}}</h1></div>
        </div>
    </div>
    <div class="jumbotron">
        @if($details->note != '')
            <div class="row justify-content-center">
                <div class="col-10">
                    <h3>Instructions:</h3>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10" id="guest-link-instructions">
                    {!! $details->note !!}
                </div>
            </div>
        @endif
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
                                        <td><a href="{{route('download', ['id' => $file->file_id, 'name' => $file->file_name])}}">{{$file->file_name}}</a></td>
                                        <td>{{$file->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <download-all
                                            download_all_route="{{route('downloadAll')}}"
                                            :file_arr="[@foreach($files as $file) {{$file->file_id}}@if(!$loop->last), @endif @endforeach]"
                                        ></download-all>
                                    </td>
                                </tr>
                            </tfoot>
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
                    <user-upload
                        submit_route="{{route('file-links.show', $details->link_hash)}}"             
                    ></user-upload>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


