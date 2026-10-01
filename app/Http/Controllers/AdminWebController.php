<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Device;
use App\Models\LicenseCode;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Services\AdminLoginSecurity;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminWebController extends Controller
{
    /**
     * Admin login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('admin_web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Admin web login.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $admin = Admin::where(
            'email',
            $validated['email']
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Admin Not Found
        |--------------------------------------------------------------------------
        */

        if (!$admin) {

            AdminActivityLogger::log(
                'ADMIN_LOGIN_FAILED',
                'Login attempt with unknown email.',
                $request,
                null,
                AdminActivityLogger::WARNING,
                [
                    'reason' => 'email_not_found',
                    'email' => $validated['email'],
                ]
            );

            return back()
                ->withErrors([
                    'email' => 'Invalid admin credentials.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Lock Check
        |--------------------------------------------------------------------------
        */

        if (
            $admin->role !== 'super_admin'
            &&
            AdminLoginSecurity::isLocked($admin)
        ) {

            AdminActivityLogger::log(
                'ADMIN_LOGIN_BLOCKED',
                'Login blocked because account is locked.',
                $request,
                $admin,
                AdminActivityLogger::CRITICAL,
                [
                    'locked_until' => $admin->locked_until,
                ]
            );

            return back()
                ->withErrors([
                    'email' => 'Account temporarily locked. Try again later.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if ($admin->status !== 'active') {

            return back()
                ->withErrors([
                    'email' => 'Admin account inactive.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Password Check
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $validated['password'],
            $admin->password
        )) {

            AdminLoginSecurity::failed($admin);

            if (
                $admin->role !== 'super_admin'
                &&
                AdminLoginSecurity::isLocked($admin)
            ) {

                AdminActivityLogger::log(
                    'ADMIN_ACCOUNT_LOCKED',
                    'Admin account locked after failed login attempts.',
                    $request,
                    $admin,
                    AdminActivityLogger::CRITICAL,
                    [
                        'attempts' => $admin->failed_login_attempts,
                        'locked_until' => $admin->locked_until,
                    ]
                );

                AdminNotificationService::send(
                    $admin,
                    'SECURITY',
                    'Account Locked',
                    'Your admin account has been locked after multiple failed login attempts.',
                    [
                        'attempts' => $admin->failed_login_attempts,
                        'locked_until' => $admin->locked_until,
                    ]
                );
            }

            AdminActivityLogger::log(
                'ADMIN_LOGIN_FAILED',
                'Invalid password attempt.',
                $request,
                $admin,
                AdminActivityLogger::WARNING,
                [
                    'reason' => 'wrong_password',
                ]
            );

            AdminNotificationService::send(
                $admin,
                'LOGIN_FAILED',
                'Failed Login Attempt',
                'A failed login attempt was detected.',
                [
                    'ip' => $request->ip(),
                    'time' => now(),
                ]
            );

            return back()
                ->withErrors([
                    'email' => 'Invalid admin credentials.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Successful Login
        |--------------------------------------------------------------------------
        */

        AdminLoginSecurity::success($admin);

        $admin->update([
            'last_login_at' => now(),
        ]);

        Auth::guard('admin_web')->login($admin);

        /*
        |--------------------------------------------------------------------------
        | Session Fixation Protection
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        AdminActivityLogger::log(
            'ADMIN_LOGIN',
            'Admin logged in successfully from web panel.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'channel' => 'web',
            ]
        );

        AdminNotificationService::send(
            $admin,
            'LOGIN',
            'New Admin Login',
            'Your admin account was logged in successfully.',
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'time' => now(),
                'channel' => 'web',
            ]
        );

        return redirect()
            ->intended(route('admin.dashboard'));
    }

    
    /**
     * Admin dashboard.
     */
    public function dashboard(): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        /*
        |--------------------------------------------------------------------------
        | Dashboard Permission
        |--------------------------------------------------------------------------
        */

        if (
            $admin->role !== 'super_admin'
            &&
            !$admin->permissions->contains(
                'name',
                'view_dashboard'
            )
        ) {
            abort(403);
        }

        $stats = [

            'users' => User::count(),

            'devices' => Device::count(),

            'subscriptions' => Subscription::count(),

            'plans' => SubscriptionPlan::count(),

            'payments' => Payment::count(),

            'licenses' => LicenseCode::count(),

            'admins' => Admin::count(),

            'unread_notifications' =>
                AdminNotification::where(
                    'admin_id',
                    $admin->id
                )
                ->where('is_read', false)
                ->count(),

        ];

        return view(
            'admin.dashboard',
            compact(
                'admin',
                'stats'
            )
        );
    }


    /**
     * Admin users list.
     */
    public function users(Request $request): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        $query = User::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        $users = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.users.index',
            compact(
                'admin',
                'users'
            )
        );
    }


    /**
     * Show create user form.
     */
    public function userCreate(): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        return view(
            'admin.users.create',
            compact('admin')
        );
    }


    /**
     * Store new user.
     */
    public function userStore(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:120',
            ],

            'email' => [
                'nullable',
                'email',
                'max:190',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);

        User::create($validated);

        AdminActivityLogger::log(
            'ADMIN_USER_CREATED',
            'Admin created a new user.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'email' => $validated['email'] ?? null,
            ]
        );

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'User created successfully.'
            );
    }


    /**
     * Show single user.
     */
    public function userShow(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        return view(
            'admin.users.show',
            compact(
                'admin',
                'user'
            )
        );
    }


    /**
     * Show edit user form.
     */
    public function userEdit(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        return view(
            'admin.users.edit',
            compact(
                'admin',
                'user'
            )
        );
    }


    /**
     * Update user.
     */
    public function userUpdate(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:120',
            ],

            'email' => [
                'nullable',
                'email',
                'max:190',
                'unique:users,email,' . $user->id,
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (empty($validated['password'])) {

            unset($validated['password']);

        }

        $user->update($validated);

        AdminActivityLogger::log(
            'ADMIN_USER_UPDATED',
            'Admin updated a user.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'user_id' => $user->id,
            ]
        );

        return redirect()
            ->route(
                'admin.users.show',
                $user->id
            )
            ->with(
                'success',
                'User updated successfully.'
            );
    }


    /**
     * Delete user.
     */
    public function userDestroy(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureUserPermission($admin);

        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $userId = $user->id;

        $user->delete();

        AdminActivityLogger::log(
            'ADMIN_USER_DELETED',
            'Admin deleted a user.',
            $request,
            $admin,
            AdminActivityLogger::WARNING,
            [
                'user_id' => $userId,
            ]
        );

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }


    /**
     * Check user management permission.
     */
    private function ensureUserPermission(Admin $admin): void
    {
        if (
            $admin->role !== 'super_admin'
            &&
            !$admin->permissions->contains(
                'name',
                'manage_users'
            )
        ) {
            abort(403);
        }
    }


    /**
     * Admin logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin_web')->user();

        if ($admin) {

            AdminActivityLogger::log(
                'ADMIN_LOGOUT',
                'Admin logged out from web panel.',
                $request,
                $admin,
                AdminActivityLogger::INFO,
                [
                    'channel' => 'web',
                ]
            );
        }

        Auth::guard('admin_web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }
}