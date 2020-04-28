@extends('layouts.app')

@section('content')
<tech-tip-details
    :tip_data="{{$details}}"
    :is_fav="{{$isFav}}"
    @can('hasAccess', 'edit_tech_tip')
    :can_edit="true"
    @endcan
></tech-tip-details>
@endsection
