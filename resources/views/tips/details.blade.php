@extends('layouts.app')

@section('content')
<tech-tip-details
    :tip_details="{{json_encode($details)}}"
    :is_fav="{{$isFav}}"
    :can_edit="{{$canEdit}}"
    :can_del="{{$canDel}}"
></tech-tip-details>
<tech-tip-files :tip_files="{{json_encode($files)}}"></tech-tip-files>
<tech-tip-comments tip_id="{{$details->tip_id}}"></tech-tip-comments>
@endsection
