<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadLogController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        return 'download log';
    }
}
