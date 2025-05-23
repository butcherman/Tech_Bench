<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkController extends Controller
{
    public function __construct(protected FileLinkService $svc) {}

    /**
     * Show a listing of the user's File Links.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', FileLink::class);

        return Inertia::render('FileLink/Index', [
            'link-list' => Inertia::defer(fn() => $request->user()->FileLinks),
        ]);
    }

    /**
     *
     */
    public function create()
    {
        //
        // return 'create';
        return Inertia::render('FileLink/Create');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     *
     */
    public function show(string $id)
    {
        //
        // return 'show';
        return Inertia::render('FileLink/Show');
    }

    /**
     *
     */
    public function edit(string $id)
    {
        //
        // return 'edit';
        return Inertia::render('FileLink/Edit');
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(FileLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->svc->destroyFileLink($link);

        return redirect(route('links.index'))->with('danger', 'Link Deleted');
    }
}
