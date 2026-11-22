<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserAdministrationController extends Controller
{
    public function __construct(protected UserAdministrationService $svc) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('User/Admin/Index', [
            'user-list' => Inertia::defer(
                fn () => $this->svc->getAllUsers(),
                rescue: true
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
