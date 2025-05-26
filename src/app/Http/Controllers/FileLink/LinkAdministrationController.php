<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LinkAdministrationController extends Controller
{
    /**
     * Show a listing of all File Links for all Users.
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLink/Index', [
            'link-list' => Inertia::defer(
                fn() => FileLink::orderBy('expire', 'desc')->get()->load('User')
            ),
            'is-admin' => true,
        ]);
    }
}
