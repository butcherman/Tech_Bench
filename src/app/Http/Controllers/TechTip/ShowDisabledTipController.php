<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Resources\TechTipResource;
use App\Models\TechTip;
use Inertia\Inertia;
use Inertia\Response;

class ShowDisabledTipController extends Controller
{
    /**
     * Show the details for a disabled Tech Tip
     */
    public function __invoke(TechTip $techTip): Response
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Deleted/Show', [
            'tip-data' => new TechTipResource($techTip),
            'tip-equipment' => $techTip->EquipmentType,
            'tip-files' => $techTip->FileUpload,
            'tip-comments' => $techTip->TechTipComment,
        ]);
    }
}
