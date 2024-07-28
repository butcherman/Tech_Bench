<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TechTipsSettingsController extends Controller
{
    /**
     * Show the form for editing the resource.
     */
    public function edit(Request $request)
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Admin');
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }
}
