<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PinnedLinksRequest;
use Illuminate\Http\Request;

class PinnedLinksController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PinnedLinksRequest $request)
    {
        $request->processPin();

        return back();
    }
}
