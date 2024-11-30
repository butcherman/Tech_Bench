<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DownloadTechTipController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TechTip $techTip): Response
    {
        Log::info(
            'Tech Tip ID '.$techTip->tip_id.' being downloaded by '.
                $request->user()->username
        );

        return PDF::loadView('pdf.tech_tip', [
            'tipData' => $techTip->load(['EquipmentType', 'FileUpload']),
        ])->stream();
    }
}
