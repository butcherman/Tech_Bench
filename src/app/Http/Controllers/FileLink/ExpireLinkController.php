<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ExpireLinkController extends Controller
{
    /**
     * Set the Link's Expire Date to yesterday.
     */
    public function __invoke(FileLinkService $svc, FileLink $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $svc->expireFileLink($link);

        Log::info('A File Link was manually expired', $link->toArray());

        return back()->with('success', 'Link Expired');
    }
}
