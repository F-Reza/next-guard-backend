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