<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FileLinkSettingsController extends Controller
{
    /**
     * Show form to edit File Link Settings.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the File Link Settings
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }
}
