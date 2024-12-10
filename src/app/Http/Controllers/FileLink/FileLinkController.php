<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkController extends Controller
{
    /**
     * Display a listing of the Models.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLinks/Index', [
            'link-list' => fn () => $request->user()->FileLinks,
        ]);
    }

    /**
     * Show the form for creating a new Model.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Model.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the Model.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the Model.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the Model.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the Model.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Restore the Model.
     */
    public function restore()
    {
        //
    }

    /**
     * Force Delete the Model.
     */
    public function forceDelete()
    {
        //
    }
}
