<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DownloadTipController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TechTip $techTip)
    {
        $this->authorize('view', $techTip);

        Log::channel('tip')
            ->info('Tech Tip ID ' . $techTip->tip_id . ' downloaded by ' .
                $request->user()->username);

        return Pdf::loadView('pdf.tech_tip', [
            'tipData' => $techTip->load(['EquipmentType', 'FileUpload']),
        ])->stream();
    }
}
