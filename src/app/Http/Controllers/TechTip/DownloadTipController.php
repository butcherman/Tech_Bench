<?php

namespace App\Http\Controllers\TechTip;

use App\Exceptions\TechTip\DownloadTipNotAllowedException;
use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DownloadTipController extends Controller
{
    /**
     * Download a Tech Tip as a PDF File.
     */
    public function __invoke(Request $request, TechTip $tech_tip): Response
    {
        // If download feature is disabled, abort
        // throw_if(!config('tech-tips.allow_download'), DownloadTipNotAllowedException::class);
        if (! config('tech-tips.allow_download')) {
            throw new DownloadTipNotAllowedException;
        }

        Log::info('Tech Tip ID '.$tech_tip->tip_id.' is being downloaded by '.
            $request->user()->username);

        // Change all image tags to note the file location for the PDF to find
        $tech_tip->details = str_replace(
            'src="/storage/images',
            'src="'.storage_path().
            '/app/public/images',
            $tech_tip->details
        );

        return Pdf::loadView('pdf.tech_tip', [
            'tipData' => $tech_tip->load(['Equipment', 'Files']),
        ])->setOption(['isRemoteEnabled' => true])->stream();
    }
}
