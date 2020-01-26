<?php

namespace App\Http\Controllers\Admin;

use App\Settings;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\UserCollection;
use App\UserRolePermissionTypes;
use App\UserRolePermissions;
use App\UserRoleType;

class AdminController extends Controller
{
    public function __construct()
    {
        //  Only Authorized users with specific admin permissions are allowed
        $this->middleware(['auth', 'can:allow_admin']);
    }

    //  Admin landing page
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.index');
    }

    //  Display all file links
    public function userLinks()
    {
        $userLinks = new UserCollection(
                        User::withCount([
                                'FileLinks',
                                'FileLinks as expired_file_links_count' => function($query)
                                {
                                    $query->where('expire', '<', Carbon::now());
                                }
                            ])
                            ->get()
                            ->makeVisible('user_id')
                    );

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userLinks', [
            'links' => $userLinks,
        ]);
    }

    //  Show the links for the selected user
    public function showLinks($id)
    {
        $user     = User::find($id);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.linkDetails', [
            'user' => $user,
            // 'name'   => $userName
        ]);
    }

    //  Get the form to change the user password policy
    public function passwordPolicy()
    {
        $this->authorize('hasAccess', 'Manage Users');
        return view('admin.userSecurity', [
            'passExpire' => config('auth.passwords.settings.expire'),
        ]);
    }

    //  Submit the form to change the user password policy
    public function submitPolicy(Request $request)
    {
        $this->authorize('hasAccess', 'Manage Users');

        $request->validate([
            'passExpire' => 'required|numeric'
        ]);

        Settings::firstOrCreate(
            ['key'   => 'auth.passwords.settings.expire'],
            ['key'   => 'auth.passwords.settings.expire', 'value' => $request->passExpire]
        )->update(['value' => $request->passExpire]);

        //  If the setting is changing from never to xx days, update all users
        if ($request->passExpire == 0) {
            User::whereNotNull('password_expires')->update([
                'password_expires' => null
            ]);
        }
        else
        {
            $newExpire = Carbon::now()->addDays($request->passExpire);
            User::whereNull('password_expires')->update([
                'password_expires' => $newExpire
            ]);
        }

        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('User Settings have been changed by User ID-' . Auth::user()->user_id);
        return redirect()->back()->with('success', 'User Security Updated');
    }

    public function roleSettings()
    {
        $this->authorize('hasAccess', 'Manage User Roles');
        $roles = UserRoleType::with(['UserRolePermissions' => function($query)
        {
            $query->join('user_role_permission_types', 'user_role_permission_types.perm_type_id', '=', 'user_role_permissions.perm_type_id');
        }])->get();
        $perms = UserRolePermissionTypes::all();

        return view('admin.roleSettings', [
            'roles' => $roles,
            'perms' => $perms,
        ]);
    }

    public function submitRoleSettings(Request $request)
    {
        $this->authorize('hasAccess', 'Manage User Roles');

        $request->validate([
            // 'role_id' => 'required',
            'name'        => 'required',
            'description' => 'required',
            'permissions' => 'required',
        ]);

        Log::debug('submitted data', $request->toArray());

        if($request->role_id)
        {
            $role = UserRoleType::find($request->role_id);
            if($role->allow_edit)
            {
                $role->update([
                    'name'        => $request->name,
                    'description' => $request->description,
                ]);
                foreach($request->permissions as $perm)
                {
                    UserRolePermissions::where('role_id', $request->role_id)->where('perm_type_id', $perm['perm_type_id'])->update([
                        'allow' => isset($perm['allow']) && $perm['allow'] ? 1 : 0,
                    ]);
                }

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'reason' => 'Unable to Edit this Role']);
        }

        $role = UserRoleType::create(
        [
            'name'        => $request->name,
            'description' => $request->description,
        ]);
        foreach ($request->permissions as $perm)
        {
            UserRolePermissions::create([
                'role_id'      => $role->role_id,
                'perm_type_id' => $perm['perm_type_id'],
                'allow'        => isset($perm['allow']) && $perm['allow'] ? 1 : 0,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
