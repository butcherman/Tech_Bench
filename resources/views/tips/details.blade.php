@extends('layouts.app')

@section('content')
<tech-tip-details
    :tip_details="{{json_encode($details)}}"
    :is_fav="{{$isFav}}"
@can('hasAccess', 'edit_tech_tip')
    :can_edit="true"
@endcan
@can('hasAccess', 'delete_tech_tip')
    :can_del="true"
@endcan
></tech-tip-details>
<tech-tip-files :tip_files="{{json_encode($files)}}"></tech-tip-files>
<tech-tip-comments tip_id="{{$details->tip_id}}" user_id="{{Auth::user()->user_id}}"></tech-tip-comments>
@endsection
