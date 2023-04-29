<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogoRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;

class SetLogoController extends Controller
{
    use AppSettingsTrait;

    /**
     * Save a new Logo
     */
    public function __invoke(LogoRequest $request)
    {
        $location = $request->saveLogo($request->logo);
        $this->saveSettings('app.logo', $location);

        Log::notice('New logo has been uploaded by '.$request->user()->username, [
            'file' => $location,
        ]);

        return back()->with('success', __('admin.logo_success'));
    }
}
