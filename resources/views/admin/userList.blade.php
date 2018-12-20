@extends('layouts.app')
@section('breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">System Administration</a></li>
    <li class="breadcrumb-item active">User Accounts</li>
</ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>User Accounts</h1></div>
        </div>
    </div>
        @if(session()->has('success'))
            <div class="row justify-content-center">
                <div class="col-8">
                    <h2 class="text-center alert alert-success">{{session('success')}}</h2>
                </div>
            </div>
        @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email Address</th>
                            <th>Last Login Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-center"><a href="{{route('admin.users.create')}}" class="btn btn-info">Add New User</a></td>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><a href="{{route('admin.users.edit', $user->user_id)}}">{{$user->first_name.' '.$user->last_name}}</a></td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if(!empty($user->UserLogins->last()))
                                        {{date('M j, Y - g:i A', strtotime($user->UserLogins->last()->created_at))}}
                                    @else
                                        Never
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin.users.edit', $user->user_id)}}" title="Edit User" data-tooltip="tooltip" class="text-muted"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="{{route('admin.users.show', $user->user_id)}}" title="Reset Password" data-tooltip="tooltip" class="text-muted"><i class="fa fa-key" aria-hidden="true"></i></a>
                                    <a href="#edit-modal" title="Deactivate User" data-tooltip="tooltip" data-toggle="modal" data-user="{{$user->user_id}}" class="text-muted deactivate-user"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
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
    $('.deactivate-user').on('click', function()
    {
        var userID = $(this).data('user');
        var url = '{{route('admin.users.destroy', ['id' => ':user'])}}';
        url = url.replace(':user', userID);
        $('#edit-modal').find('.modal-title').text('Deactivate User');
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
                        window.location.replace('{{route('admin.users.index')}}');
                    }
                });
            });
        });
    });
</script>
@endsection
