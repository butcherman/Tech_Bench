<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpireFileLinkController extends Controller
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(FileLink $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $this->svc->expireFileLink($link);

        Log::info('A File link was manually expired', $link->toArray());

        return back()->with('success', 'Link Expired');
    }
}
