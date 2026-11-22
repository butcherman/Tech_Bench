<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Admin\UserAdministrationRequest;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use App\Traits\UserRoleTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserAdministrationController extends Controller
{
    use UserRoleTrait;

    public function __construct(protected UserAdministrationService $svc) {}

    /**
     * Display a listing of all active users.
     */
    public function index(): Response
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
     * Show the form for creating a new user.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('User/Admin/Create', [
            'roles' => fn () => $this->getAvailableRoles($request->user()),
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserAdministrationRequest $request)
    {
        $user = $this->svc->createUser($request->safe()->collect());

        return redirect(route('admin.user.show', $user))
            ->with(
                'success',
                __('admin.user.created', ['user' => $user->full_name])
            );
    }

    /**
     * Display the users profile.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
