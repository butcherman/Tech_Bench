@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col grid-margin">
        <h4>Logs List</h4>
    </div>
</div>
<div class="row">
    <div class="col grid-margin">
        <div class="card">
            <div class="card-body">
                <b-nav pills>
                    <b-nav-item href="{{route('log-viewer::dashboard')}}">Logs Dashboard</b-nav-item>
                    {{-- <b-nav-item>Open Todays Log</b-nav-item> --}}
                    <b-nav-item href="{{route('log-viewer::logs.list')}}" active>List Logs</b-nav-item>
                </b-nav>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                @foreach($headers as $key => $header)
                                <th scope="col" class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                    @if ($key == 'date')
                                        <span class="badge badge-info">{{ $header }}</span>
                                    @else
                                        <span class="badge badge-level-{{ $key }}">
                                            {{ log_styler()->icon($key) }} {{ $header }}
                                        </span>
                                    @endif
                                </th>
                                @endforeach
                                <th scope="col" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $date => $row)
                                <tr>
                                    @foreach($row as $key => $value)
                                        <td class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                            @if ($key == 'date')
                                                <span class="badge badge-primary">{{ $value }}</span>
                                            @elseif ($value == 0)
                                                <span class="badge empty">{{ $value }}</span>
                                            @else
                                                <a href="{{ route('log-viewer::logs.filter', [$date, $key]) }}">
                                                    <span class="badge badge-level-{{ $key }}">{{ $value }}</span>
                                                </a>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-right">
                                        <a href="{{ route('log-viewer::logs.show', [$date]) }}" class="btn btn-sm btn-info" title="View Log" v-b-tooltip:hover>
                                            <i class="fas fa-search"></i>
                                        </a>
                                        <a href="{{ route('log-viewer::logs.download', [$date]) }}" class="btn btn-sm btn-success" title="Download Log" v-b-tooltip:hover>
                                            <i class="fas fa-download"></i>
                                        </a>
                                        {{-- <a href="#delete-log-modal" class="btn btn-sm btn-danger" data-log-date="{{ $date }}" title="Delete Log" v-b-tooltip:hover>
                                            <i class="fas fa-trash"></i>
                                        </a> --}}
                                        <delete-log date="{{$date}}"></delete-log>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <span class="badge badge-secondary">{{ trans('log-viewer::general.empty-logs') }}</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
    {{-- DELETE MODAL --}}
    <div id="delete-log-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="delete-log-form" action="{{ route('log-viewer::logs.delete') }}" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="date" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">DELETE LOG FILE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary mr-auto" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-danger" data-loading-text="Loading&hellip;">DELETE FILE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
