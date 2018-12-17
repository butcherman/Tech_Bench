@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">System Backups</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>System Backups</h1></div>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-4">
            <a href="{{route('installer.backupNow')}}" class="btn btn-warning btn-block" id="backup-now">Backup Now</a>
        </div>
    </div>
    <div class="row justify-content-center pad-top">
        <div class="col-md-10">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="backup-table">
                    <thead>
                        <tr>
                            <th>Name:</th>
                            <th>Actions:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center">
                                <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i> Loading...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('_inc.modal')
@endsection

@section('script')
<script>
    loadBackups();
    
    function loadBackups()
    {
        $('#backup-table').find('tbody').load('{{route('installer.loadBackups')}}');
    }
    
    $('#backup-now').on('click', function(e)
    {
        e.preventDefault();
        $(this).append('&nbsp; &nbsp; <i class="fa fa-cog fa-spin"></i>');
        $.get($(this).attr('href'), function(res)
        {
            $('#backup-now').html('Backup Now');
            loadBackups();
            if(res != 'success')
            {
                alert('There Was An Issue Performing the Backup');
            }
        });
    });
    
    $('#backup-table').on('click', '.delete-backup', function()
    {
        var name = $(this).data('name');
        var url = '{{route('installer.destroyBackup', ['name' => ':name'])}}';
        url = url.replace(':name', name);

        $('#edit-modal').find('.modal-title').text('Delete Backup');
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
                        loadBackups();
                    }
                });
            });
        });
    });
</script>
@endsection
