<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use PDF;

class DownloadTipController extends Controller
{
    /**
     * Download a Tech Tip as a PDF file
     */
    public function __invoke(Request $request)
    {
        $tip = TechTip::with('EquipmentType')->with('FileUploads')->findOrFail($request->id);
        $pdf = PDF::loadView('pdf.tech_tip', [
            'details' => $tip,
        ]);

        return $pdf->stream();
    }
}
