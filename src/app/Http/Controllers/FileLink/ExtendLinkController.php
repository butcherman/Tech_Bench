<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ExtendLinkController extends Controller
{
    /**
     * Extend the links expire date 30 days.
     */
    public function __invoke(FileLinkService $svc, FileLink $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $svc->extendFileLink($link);

        Log::info('A File Link was manually extended 30 days', $link->toArray());

        return back()->with('success', 'Link Extended');
    }
}
