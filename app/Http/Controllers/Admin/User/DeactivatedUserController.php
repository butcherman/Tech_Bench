<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeactivatedUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Index', [
            'userList' => User::onlyTrashed()->with('UserRole')->get(),
        ]);
    }
}