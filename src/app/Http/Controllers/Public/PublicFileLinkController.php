<?php

namespace App\Http\Controllers\Public;

use App\Exceptions\FileLink\FileLinkExpiredException;
use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicFileLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, FileLink $link)
    {
        // Make sure link is valid
        if (Carbon::parse($link->expire) < Carbon::now()) {
            throw new FileLinkExpiredException($request, $link);
        }

        return Inertia::render('Public/FileLinks/Show', [
            'link-data' => $link->only([
                'instructions',
                'allow_upload',
                'link_hash',
            ]),
            'link-files' => $link->FileUpload()
                ->wherePivot('upload', false)
                ->get()
                ->makeHidden('pivot'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }
}
