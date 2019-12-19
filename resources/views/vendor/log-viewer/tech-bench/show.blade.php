@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <h4>Log [{{ $log->date }}]</h4>
    </div>
</div>
<div class="row">
    <div class="col grid-margin">
        <div class="card">
            <div class="card-body">
                <b-nav pills>
                    <b-nav-item href="{{route('log-viewer::dashboard')}}">Logs Dashboard</b-nav-item>
                    {{-- <b-nav-item>Open Todays Log</b-nav-item> --}}
                    <b-nav-item href="{{route('log-viewer::logs.list')}}">List Logs</b-nav-item>
                </b-nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-header"><i class="fa fa-fw fa-flag"></i> Levels</div>
            <div class="card-body">
                <div class="list-group list-group-flush log-menu">
                    @foreach($log->menu() as $levelKey => $item)
                        @if ($item['count'] === 0)
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                <span class="level-name">{!! $item['icon'] !!} {{ $item['name'] }}</span>
                                <span class="badge empty">{{ $item['count'] }}</span>
                            </a>
                        @else
                            <a href="{{ $item['url'] }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center level-{{ $levelKey }}{{ $level === $levelKey ? ' active' : ''}}">
                                <span class="level-name">{!! $item['icon'] !!} {{ $item['name'] }}</span>
                                <span class="badge badge-level-{{ $levelKey }}">{{ $item['count'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="row grid-margin stretch-card">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Log info :
                        <div class="group-btns pull-right">
                            <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> DOWNLOAD
                            </a>
                            <delete-log date="{{$log->date}}"></delete-log>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed mb-0">
                                <tbody>
                                    <tr>
                                        <td>File path :</td>
                                        <td colspan="7">{{ $log->getPath() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Log entries :</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $entries->total() }}</span>
                                        </td>
                                        <td>Size :</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $log->size() }}</span>
                                        </td>
                                        <td>Created at :</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $log->createdAt() }}</span>
                                        </td>
                                        <td>Updated at :</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $log->updatedAt() }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    @if ($entries->hasPages())
                    <div class="card-header">
                        <span class="badge badge-info float-right">
                            Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}
                        </span>
                    </div>
                    @endif
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="entries" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ENV</th>
                                        <th style="width: 120px;">Level</th>
                                        <th style="width: 65px;">Time</th>
                                        <th>Header</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($entries as $key => $entry)
                                        <?php /** @var  Arcanedev\LogViewer\Entities\LogEntry  $entry */ ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-env">{{ $entry->env }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-level-{{ $entry->level }}">
                                                    {!! $entry->level() !!}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $entry->datetime->format('H:i:s') }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $entry->header }}
                                            </td>
                                            <td class="text-right">
                                                @if ($entry->hasStack())
                                                    <a class="btn btn-sm btn-light" role="button" data-toggle="collapse" href="#log-stack-{{ $key }}" aria-expanded="false" aria-controls="log-stack-{{ $key }}">
                                                        <i class="fa fa-toggle-on"></i> Stack
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($entry->hasStack())
                                            <tr>
                                                <td colspan="5" class="stack py-0">
                                                    <div class="stack-content collapse" id="log-stack-{{ $key }}">
                                                        {!! $entry->stack() !!}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
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
    </div>
</div>
@endsection
