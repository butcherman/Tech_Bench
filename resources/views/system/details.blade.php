@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.index')}}">Systems</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.select', ['cat' => $category])}}">{{$category}}</a></li>
    <li class="breadcrumb-item active">{{$sysName}}</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>{{str_replace('-', ' ', $sysName)}}</h1></div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-10">
            <div class="card text-center">
                <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($fileTypes as $type)
                            <li class="nav-item">
                                @if($loop->first)
                                        <a class="nav-link text-muted active" id="{{str_replace(' ', '-', $type->description)}}-tab" data-toggle="tab" href="#{{str_replace(' ', '-', $type->description)}}" role="tab" aria-controls="{{$type->description}}" aria-selected="true">{{$type->description}}</a>
                                @else
                                    <a class="nav-link text-muted" id="{{str_replace(' ', '-', $type->description)}}-tab" data-toggle="tab" href="#{{str_replace(' ', '-', $type->description)}}" role="tab" aria-controls="{{$type->description}}" aria-selected="false">{{$type->description}}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body tab-content">
                    @foreach($fileTypes as $type)
                            @if($loop->first)
                                <div class="tab-pane fade show active" id="{{str_replace(' ', '-', $type->description)}}" data-type="{{urlencode($type->description)}}" role="tabpanel" aria-labelledby="{{$type->description}}-tab">                 
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>File</th>
                                                    <th>Added By</th>
                                                    <th>Date Added</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4">
                                                    <a href="#edit-modal" data-toggle="modal" data-type="{{ $type->description }}" class="btn btn-info add-sys-file">Add File</a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="tab-pane fade" id="{{str_replace(' ', '-', $type->description)}}" data-type="{{urlencode($type->description)}}" role="tabpanel" aria-labelledby="{{$type->description}}-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>File</th>
                                                    <th>Added By</th>
                                                    <th>Date Added</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4">
                                                    <a href="#edit-modal" data-toggle="modal" data-type="{{ $type->description }}" class="btn btn-info add-sys-file">Add File</a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Latest Tech Tips</h1></div>
    @include('tip.searchResults')
    @include('_inc.modal')
</div>
@endsection

@section('script')
<script>
    //  Initial AJAX call to load all system files 
    loadSysFiles();

    //  Function to load all system files
    function loadSysFiles()
    {
        $('.tab-pane').each(function()
        {
            var myTable = $(this).find('table');
            $(this).find('tbody').load('{{ url('system/load-file') }}/{{ urlencode($sysName) }}/'+$(this).data('type'), function()
            {
                $('[data-tooltip="tooltip"]').tooltip();
                myTable.DataTable({
                    'paging':false,
                    'language':{
                        'emptyTable':'No Files'
                    },
                    searching: false
                });
            });
        });
    }
    //  Open the add file form
    $('.add-sys-file').on('click', function()
    {
        var dataType = $(this).data('type');
        $('.modal-title').text('Add New File For: '+dataType);
        $('.modal-body').load('{{route('system.files.create')}}', function()
        {
            //  Populate the hidden inputs
            $('#fileType').val(dataType);
            $('#category').val('{{ urldecode($category) }}');
            $('#system').val('{{ urldecode($sysName) }}');
            
            fileDrop($('#system-file-upload-form'));
        });
    });
    //  Open the Edit File form
    $(document).on('click', '.edit-file', function()
    {
        var url = '{{route('system.files.edit', ['id' => ':id'])}}'
        url = url.replace(':id', $(this).data('file'));
        $('#edit-modal').find('.modal-title').text('Edit File Data');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            $('#system-file-edit-form').on('submit', function(e)
            {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize())
                 .fail(function(data)
                {
                    uploadFailed(data);
                })
                 .done(function(res)
                {
                    uploadComplete(res);
                });
            });
        });
    });
    //  Open the replace file form
    $(document).on('click', '.replace-file', function()
    {
        var url = '{{route('system.files.replaceForm', ['id' => ':id'])}}'
        url = url.replace(':id', $(this).data('file'));
        $('#edit-modal').find('.modal-title').text('Replace File With Newer Version');
        $('#edit-modal').find('.modal-body').load(url, function()
        {
            fileDrop($('#system-file-replace-form'));
        });
    });
    //  Load confirm box to delete a fil
    $(document).on('click', '.delete-file', function()
    {
        var url = '{{route('system.files.destroy', ['id' => ':id'])}}'
        url = url.replace(':id', $(this).data('file'));
        $('#edit-modal').find('.modal-title').text('Confirm Delete File');
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
                        loadSysFiles();
                    }
                })
            });
        });
    });
    //  Finish the file upload by resetting form and reloading files
    function uploadComplete(res)
    {
        resetEditModal();
        loadSysFiles();
    }
    //  If the upload Fails, show the errors
    function uploadFailed(data)
    {
        $('#form-errors').removeClass('d-none');
        $('form').find('input[type=submit]').prop('disabled', false);
        resetProgressBar();
        var err = $.parseJSON(data.responseText);
        $.each(err.errors, function(key, val)
        {
            $('#form-errors').append('<h5>'+val+'</h5>');
        });
        console.log(data);
    }
</script>
@endsection
