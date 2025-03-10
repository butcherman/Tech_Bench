<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowDeletedTipController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTip $techTip): Response
    {
        $this->authorize('manage', TechTip::class);

        $techTip->loadShowData(true);

        return Inertia::render('TechTips/Deleted/Show', [
            'tip-data' => $techTip,
            'tip-equipment' => $techTip->EquipmentType,
            'tip-files' => $techTip->FileUpload,
            'tip-comments' => $techTip->TechTipComment,
        ]);
    }
}
