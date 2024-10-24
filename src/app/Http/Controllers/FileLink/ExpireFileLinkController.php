<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Illuminate\Http\RedirectResponse;

class ExpireFileLinkController extends Controller
{
    /**
     * Take a Valid File link and expire it so it can no longer be accessed publicly
     */
    public function __invoke(FileLink $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $link->expireLink();

        return back()->with('success', 'Link Expired');
    }
}
