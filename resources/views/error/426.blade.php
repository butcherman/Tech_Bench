@extends('layouts.guest')

@section('content')
<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h1>Error:</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="jumbotron text-center text-danger">
            <img src="/img/err_img/sry_error.png" alt="sorry" class="float-left" />
            <p>
                Seems your using an old unsupported  browser.
            </p>
            <p>
                You will need to upgrade to a more modern browser to use the Tech Bench features.
            </p>
            <p>
                Here are some suggestions:
                <ul style="list-style: none">
                    <li><a href="https://google.com/chrome/">Google Chrome</a></li>
                    <li><a href="https://mozilla.org/en-US/firefox/new/">Firefox</a></li>
                    <li><a href="https://microsoft.com/en-us/edge/">Microsft Edge</a></li>
                    <li><a href="https://opera.com">Opera</a></li>
                </ul>
            </p>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
