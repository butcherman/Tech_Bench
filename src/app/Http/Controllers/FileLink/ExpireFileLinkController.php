<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Illuminate\Http\Request;

class ExpireFileLinkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, FileLink $link)
    {
        $this->authorize('update', $link);

        $link->expireLink();

        return back()->with('success', 'Link Expired');
    }
}
