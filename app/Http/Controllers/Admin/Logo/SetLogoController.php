<?php

namespace App\Http\Controllers\Admin\Logo;

use App\Events\Admin\NewLogoUploadedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadImageRequest;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class SetLogoController extends Controller
{
    /**
     * Save a new Application Logo
     */
    public function __invoke(UploadImageRequest $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        //  Storage Path
        $path     = 'images/logo';
        $location = '/storage/'./** @scrutinizer ignore-type */ Storage::disk('public')->putFile($path, new File($request->file));

        AppSettings::firstOrCreate(
            ['key' => 'app.logo'],
            ['key' => 'app.logo', 'value' => $location]
        )->update([
            'value' => $location
        ]);

        event(new NewLogoUploadedEvent($location));
        return response()->noContent();
    }
}
