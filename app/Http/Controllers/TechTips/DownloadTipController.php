<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;

use PDF;

class DownloadTipController extends Controller
{
    /**
     *  Convert the Tech Tip into a PDF Document and download
     */
    public function __invoke(Request $request)
    {
        // return $request->id;


        $tip = TechTip::where('tip_id', $request->id)->with('EquipmentType')->with('FileUploads')->firstOrFail();

        $pdf = PDF::loadView('pdf.tech_tip', [
            'details' => $tip,
        ]);


        return $pdf->stream();
    }
}
