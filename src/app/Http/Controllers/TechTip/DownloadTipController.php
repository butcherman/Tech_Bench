<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadTipController extends Controller
{
    /**
     * Download a Tech Tip as a PDF File.
     */
    public function __invoke(Request $request, TechTip $tech_tip): Response
    {
        // If feature is disabled, abort
        abort_if(!config('tech-tips.allow_download'), 404);

        Log::info('Tech Tip ID ' . $tech_tip->tip_id . ' is being downloaded by ' .
            $request->user()->username);

        return Pdf::loadView('pdf.tech_tip', [
            'tipData' => $tech_tip->load(['Equipment', 'Files']),
        ])->setOption(['isRemoteEnabled' => true])->stream();
    }
}
