@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <h4>Logs Dashboard</h4>
    </div>
</div>
<div class="row">
    <div class="col grid-margin">
        <div class="card">
            <div class="card-body">
                <b-nav pills>
                    <b-nav-item href="{{route('log-viewer::dashboard')}}" active>Logs Dashboard</b-nav-item>
                    {{-- <b-nav-item>Open Todays Log</b-nav-item> --}}
                    <b-nav-item href="{{route('log-viewer::logs.list')}}">List Logs</b-nav-item>
                </b-nav>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <logs-doughnut-chart :chartdata="{{$chartData}}"></logs-doughnut-chart>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach($percents as $level => $item)
                        <div class="col-md-6 mb-3">
                            <div class="box level-{{ $level }} {{ $item['count'] === 0 ? 'empty' : '' }}">
                                <div class="box-icon">
                                    {!! log_styler()->icon($level) !!}
                                </div>
                                <div class="box-content">
                                    <span class="box-text">{{ $item['name'] }}</span>
                                    <span class="box-number">
                                        {{ $item['count'] }} entries - {!! $item['percent'] !!} %
                                    </span>
                                    <div class="progress" style="height: 3px;">
                                        <div class="progress-bar" style="width: {{ $item['percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
