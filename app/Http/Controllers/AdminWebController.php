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
use App\Services\SubscriptionChangeService;
use App\Services\ProtectionActivationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
     * Admin devices list.
     */
    public function devices(Request $request): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureDevicePermission($admin);

        $query = Device::query()
            ->with([
                'user',
                'protectionSetting',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'model',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'manufacturer',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

                        $userQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Device Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Management Mode Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('management_mode')) {

            $query->where(
                'management_mode',
                $request->input('management_mode')
            );
        }

        $devices = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.devices.index',
            compact(
                'admin',
                'devices'
            )
        );
    }



    /**
     * Show single device.
     */
    public function deviceShow(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureDevicePermission($admin);

        $device = Device::with([
            'user',
            'protectionSetting',
            'deviceSessions' => function ($query) {
                $query->latest()
                ->limit(5);
            },
            'events' => function ($query) {
                $query
                    ->latest('created_at')
                    ->limit(10);
            },
            'subscriptions' => function ($query) {
                $query->latest();
            },
        ])
        ->find($id);

        if (!$device) {
            abort(404);
        }

        return view(
            'admin.devices.show',
            compact(
                'admin',
                'device'
            )
        );
    }


        /**
         * Show device sessions.
         */
        public function sessions(int $id): View
        {
            $admin = Auth::guard('admin_web')
                ->user()
                ->load('permissions');

            $this->ensureDevicePermission($admin);

            $device = Device::find($id);

            if (!$device) {
                abort(404);
            }

            $sessions = $device->deviceSessions()
                ->latest()
                ->paginate(20)
                ->withQueryString();

            return view(
                'admin.devices.sessions',
                compact(
                    'admin',
                    'device',
                    'sessions'
                )
            );
        }
            

        public function events(int $id): View
        {
                $admin = Auth::guard('admin_web')
                ->user()
                ->load('permissions');

            $this->ensureDevicePermission($admin);

            $device = Device::find($id);

            if (!$device) {
                abort(404);
            }

            $events = $device->events()
                ->latest()
                ->paginate(25)
                ->withQueryString();

            return view(
                'admin.devices.events',
                compact(
                    'admin',
                    'device',
                    'events'
                )
            );
        }




    /**
     * Revoke device from admin panel.
     */
    public function deviceRevoke(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureDevicePermission($admin);

        $device = Device::find($id);

        if (!$device) {
            abort(404);
        }

        if ($device->status === 'revoked') {

            return redirect()
                ->route(
                    'admin.devices.show',
                    $device->id
                )
                ->with(
                    'success',
                    'Device is already revoked.'
                );
        }

        DB::transaction(
            function () use ($device) {

                $device->update([
                    'status' => 'revoked',
                ]);

                $device->deviceSessions()
                    ->where(
                        'status',
                        'active'
                    )
                    ->update([
                        'status' => 'revoked',
                        'revoked_at' => now(),
                    ]);

            }
        );

        AdminActivityLogger::log(
            'ADMIN_DEVICE_REVOKED',
            'Admin revoked a device.',
            $request,
            $admin,
            AdminActivityLogger::WARNING,
            [
                'device_id' => $device->id,
                'user_id' => $device->user_id,
            ]
        );

        return redirect()
            ->route(
                'admin.devices.show',
                $device->id
            )
            ->with(
                'success',
                'Device revoked successfully.'
            );
    }



    /**
     * Reactivate revoked device.
     */
    public function deviceReactivate(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureDevicePermission($admin);

        $device = Device::findOrFail($id);

        if ($device->status === 'active') {

            return redirect()
                ->route(
                    'admin.devices.show',
                    $device->id
                )
                ->with(
                    'success',
                    'Device is already active.'
                );
        }

        DB::transaction(function () use ($device) {

            $device->update([
                'status' => 'active',
            ]);

        });

        AdminActivityLogger::log(
            'ADMIN_DEVICE_REACTIVATED',
            'Admin reactivated a revoked device.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'device_id' => $device->id,
                'user_id' => $device->user_id,
            ]
        );

        return redirect()
            ->route(
                'admin.devices.show',
                $device->id
            )
            ->with(
                'success',
                'Device reactivated successfully.'
            );
    }





    /**
     * Admin subscriptions list.
     */
    public function subscriptions(Request $request): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $query = Subscription::query()
            ->with([
                'user',
                'plan',
                'device',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }

                $q->orWhere(
                    'payment_reference',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

                        $userQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Plan
        |--------------------------------------------------------------------------
        */

        if ($request->filled('plan_id')) {

            $query->where(
                'subscription_plan_id',
                $request->integer('plan_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Source
        |--------------------------------------------------------------------------
        */

        if ($request->filled('source')) {

            $query->where(
                'source',
                $request->input('source')
            );
        }

        $subscriptions = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $plans = SubscriptionPlan::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.subscriptions.index',
            compact(
                'admin',
                'subscriptions',
                'plans'
            )
        );
    }



    /**
     * Show subscription details.
     */
    public function subscriptionShow(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::with([
            'user',
            'plan',
            'device',
            'events' => function ($query) {
                $query->latest();
            },
        ])
        ->findOrFail($id);

        return view(
            'admin.subscriptions.show',
            compact(
                'admin',
                'subscription'
            )
        );
    }




    /**
     * Cancel subscription.
     */
    public function subscriptionCancel(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::findOrFail($id);

        if ($subscription->status === 'cancelled') {

            return redirect()
                ->route(
                    'admin.subscriptions.show',
                    $subscription->id
                )
                ->with(
                    'success',
                    'Subscription is already cancelled.'
                );
        }

        if ($subscription->status === 'expired') {

            return redirect()
                ->route(
                    'admin.subscriptions.show',
                    $subscription->id
                )
                ->with(
                    'success',
                    'Expired subscription cannot be cancelled.'
                );
        }

        $oldStatus = $subscription->status;

        DB::transaction(function () use (
            $subscription,
            $oldStatus
        ) {

            $subscription->update([
                'status' => 'cancelled',
                'auto_renew' => false,
            ]);

            $subscription->events()->create([
                'event' => 'cancelled',
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'description' =>
                    'Subscription cancelled by admin.',
            ]);

        });

        AdminActivityLogger::log(
            'ADMIN_SUBSCRIPTION_CANCELLED',
            'Admin cancelled a subscription.',
            $request,
            $admin,
            AdminActivityLogger::WARNING,
            [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'old_status' => $oldStatus,
            ]
        );

        return redirect()
            ->route(
                'admin.subscriptions.show',
                $subscription->id
            )
            ->with(
                'success',
                'Subscription cancelled successfully.'
            );
    }




    /**
     * Show change subscription plan form.
     */
    public function subscriptionChangePlan(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::with([
            'user',
            'plan',
            'device',
        ])
        ->findOrFail($id);

        if ($subscription->status !== 'active') {
            abort(409, 'Only active subscriptions can change plan.');
        }

        $plans = SubscriptionPlan::query()
            ->where('status', 'active')
            ->where('id', '!=', $subscription->subscription_plan_id)
            ->orderBy('price')
            ->get();

        return view(
            'admin.subscriptions.change-plan',
            compact(
                'admin',
                'subscription',
                'plans'
            )
        );
    }


    /**
     * Change subscription plan.
     */
    public function subscriptionChangePlanStore(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::with([
            'user',
            'plan',
        ])
        ->findOrFail($id);

        if ($subscription->status !== 'active') {

            return redirect()
                ->route(
                    'admin.subscriptions.show',
                    $subscription->id
                )
                ->withErrors([
                    'subscription' =>
                        'Only an active subscription can change plan.',
                ]);
        }

        $validated = $request->validate([
            'plan_id' => [
                'required',
                'integer',
                'exists:subscription_plans,id',
            ],
        ]);

        $newPlan = SubscriptionPlan::findOrFail(
            $validated['plan_id']
        );

        if ($newPlan->status !== 'active') {

            return back()
                ->withErrors([
                    'plan_id' =>
                        'The selected plan is not active.',
                ]);
        }

        if (
            $newPlan->id ===
            $subscription->subscription_plan_id
        ) {

            return back()
                ->withErrors([
                    'plan_id' =>
                        'The selected plan is already active.',
                ]);
        }

        try {

            $newSubscription =
                SubscriptionChangeService::changePlan(
                    $subscription->user,
                    $newPlan
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'plan_id' => $e->getMessage(),
                ]);
        }

        AdminActivityLogger::log(
            'ADMIN_SUBSCRIPTION_PLAN_CHANGED',
            'Admin changed a subscription plan.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'old_subscription_id' =>
                    $subscription->id,

                'new_subscription_id' =>
                    $newSubscription->id,

                'user_id' =>
                    $subscription->user_id,

                'old_plan_id' =>
                    $subscription->subscription_plan_id,

                'new_plan_id' =>
                    $newPlan->id,
            ]
        );

        return redirect()
            ->route(
                'admin.subscriptions.show',
                $newSubscription->id
            )
            ->with(
                'success',
                'Subscription plan changed successfully.'
            );
    }




    /**
     * Show extend subscription form.
     */
    public function subscriptionExtend(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::with([
            'user',
            'plan',
            'device',
        ])
        ->findOrFail($id);

        if ($subscription->status !== 'active') {
            abort(409, 'Only active subscriptions can be extended.');
        }

        return view(
            'admin.subscriptions.extend',
            compact(
                'admin',
                'subscription'
            )
        );
    }


    /**
     * Extend active subscription.
     */
    public function subscriptionExtendStore(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::findOrFail($id);

        if ($subscription->status !== 'active') {

            return redirect()
                ->route(
                    'admin.subscriptions.show',
                    $subscription->id
                )
                ->withErrors([
                    'subscription' =>
                        'Only active subscriptions can be extended.',
                ]);
        }

        $validated = $request->validate([
            'preset_days' => [
                'nullable',
                'integer',
                'in:7,30,90,365',
            ],

            'custom_days' => [
                'nullable',
                'integer',
                'min:1',
                'max:3650',
            ],

            'reason' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Determine Extension Days
        |--------------------------------------------------------------------------
        */

        $days = null;

        if (!empty($validated['custom_days'])) {

            $days = (int) $validated['custom_days'];

        } elseif (!empty($validated['preset_days'])) {

            $days = (int) $validated['preset_days'];

        }

        if (!$days) {

            return back()
                ->withInput()
                ->withErrors([
                    'preset_days' =>
                        'Select preset days or enter custom days.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Current Expiry
        |--------------------------------------------------------------------------
        */

        $oldExpiry = $subscription->expires_at;

        $baseDate =
            $subscription->expires_at
            && $subscription->expires_at->isFuture()
                ? $subscription->expires_at->copy()
                : now();

        $newExpiry = $baseDate->copy()->addDays($days);

        /*
        |--------------------------------------------------------------------------
        | Update + Event
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $subscription,
            $oldExpiry,
            $newExpiry,
            $days,
            $validated
        ) {

            $subscription->update([
                'expires_at' => $newExpiry,
            ]);

            $subscription->events()->create([
                'event' => 'extended',
                'old_status' => $subscription->status,
                'new_status' => $subscription->status,
                'description' =>
                    'Subscription extended by '
                    .$days
                    .' day(s). Reason: '
                    .$validated['reason'],
            ]);

        });

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        AdminActivityLogger::log(
            'ADMIN_SUBSCRIPTION_EXTENDED',
            'Admin extended a subscription.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'subscription_id' =>
                    $subscription->id,

                'user_id' =>
                    $subscription->user_id,

                'days_added' =>
                    $days,

                'old_expires_at' =>
                    $oldExpiry?->toDateTimeString(),

                'new_expires_at' =>
                    $newExpiry->toDateTimeString(),

                'reason' =>
                    $validated['reason'],
            ]
        );

        return redirect()
            ->route(
                'admin.subscriptions.show',
                $subscription->id
            )
            ->with(
                'success',
                'Subscription extended successfully.'
            );
    }



    /**
     * Show subscription reactivation form.
     */
    public function subscriptionReactivate(int $id): View
    {
        $admin = Auth::guard('admin_web')
            ->user()
            ->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $subscription = Subscription::with([
            'user',
            'plan',
            'device',
        ])
        ->findOrFail($id);

        if ($subscription->status !== 'cancelled') {
            abort(
                409,
                'Only cancelled subscriptions can be reactivated.'
            );
        }

        return view(
            'admin.subscriptions.reactivate',
            compact(
                'admin',
                'subscription'
            )
        );
    }


    

    /**
     * Reactivate cancelled subscription.
     */
    public function subscriptionReactivateStore(
        Request $request,
        int $id
    ): RedirectResponse {

        /** @var Admin|null $admin */
        $admin = Auth::guard('admin_web')
            ->user();

        if (!$admin) {
            abort(401);
        }

        $admin->load('permissions');

        $this->ensureSubscriptionPermission($admin);


        $subscription = Subscription::with([
            'user',
            'plan',
            'device',
        ])
        ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if ($subscription->status !== 'cancelled') {

            return redirect()
                ->route(
                    'admin.subscriptions.show',
                    $subscription->id
                )
                ->withErrors([
                    'subscription' =>
                        'Only cancelled subscriptions can be reactivated.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'max:500',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Expiry Check
        |--------------------------------------------------------------------------
        */

        if (
            !$subscription->expires_at
            ||
            $subscription->expires_at->isPast()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'subscription' =>
                        'This subscription has already expired. Use Admin Grant instead.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Multiple Active Subscriptions
        |--------------------------------------------------------------------------
        */

        $otherActiveSubscription =
            $subscription->user
                ->subscriptions()
                ->where('status', 'active')
                ->where('id', '!=', $subscription->id)
                ->exists();


        if ($otherActiveSubscription) {

            return back()
                ->withInput()
                ->withErrors([
                    'subscription' =>
                        'This user already has another active subscription.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Reactivate
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $subscription,
            $validated
        ) {

            $oldStatus = $subscription->status;

            $subscription->update([
                'status' => 'active',
            ]);

            $subscription->events()->create([
                'event' => 'reactivated',
                'old_status' => $oldStatus,
                'new_status' => 'active',
                'description' =>
                    'Subscription reactivated by admin. Reason: '
                    .$validated['reason'],
            ]);

        });


        /*
        |--------------------------------------------------------------------------
        | Protection Sync
        |--------------------------------------------------------------------------
        */

        ProtectionActivationService::activate(
            $subscription->fresh()
        );


        /*
        |--------------------------------------------------------------------------
        | Admin Audit
        |--------------------------------------------------------------------------
        */

        AdminActivityLogger::log(
            'ADMIN_SUBSCRIPTION_REACTIVATED',
            'Admin reactivated a cancelled subscription.',
            $request,
            $admin,
            AdminActivityLogger::INFO,
            [
                'subscription_id' =>
                    $subscription->id,

                'user_id' =>
                    $subscription->user_id,

                'plan_id' =>
                    $subscription->subscription_plan_id,

                'device_id' =>
                    $subscription->device_id,

                'expires_at' =>
                    $subscription->expires_at?->toDateTimeString(),

                'reason' =>
                    $validated['reason'],
            ]
        );


        return redirect()
            ->route(
                'admin.subscriptions.show',
                $subscription->id
            )
            ->with(
                'success',
                'Subscription reactivated successfully.'
            );
    }
        


    public function subscriptionGrant(): View
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin_web')->user();

        if (!$admin) {
            abort(401);
        }

        $admin->load('permissions');

        $this->ensureSubscriptionPermission($admin);

        $users = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $plans = SubscriptionPlan::query()
            ->where('status', 'active')
            ->orderBy('price')
            ->get();

        return view(
            'admin.subscriptions.grant',
            compact(
                'admin',
                'users',
                'plans'
            )
        );
    }



    public function subscriptionGrantStore(
        Request $request
    ): RedirectResponse {

        /** @var Admin|null $admin */
        $admin = Auth::guard('admin_web')->user();

        if (!$admin) {
            abort(401);
        }

        $admin->load('permissions');

        $this->ensureSubscriptionPermission($admin);


        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'plan_id' => [
                'required',
                'integer',
                'exists:subscription_plans,id',
            ],

            'device_id' => [
                'nullable',
                'integer',
                'exists:devices,id',
            ],

            'reason' => [
                'required',
                'string',
                'max:500',
            ],
        ]);


        $user = User::findOrFail(
            $validated['user_id']
        );

        $plan = SubscriptionPlan::findOrFail(
            $validated['plan_id']
        );


        if ($plan->status !== 'active') {

            return back()
                ->withInput()
                ->withErrors([
                    'plan_id' =>
                        'The selected plan is not active.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Active Subscription Check
        |--------------------------------------------------------------------------
        */

        $alreadyActive = $user
            ->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {

                $query
                    ->whereNull('expires_at')
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            })
            ->exists();


        if ($alreadyActive) {

            return back()
                ->withInput()
                ->withErrors([
                    'user_id' =>
                        'This user already has an active subscription.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Optional Device Check
        |--------------------------------------------------------------------------
        */

        $device = null;

        if (!empty($validated['device_id'])) {

            $device = $user->devices()
                ->where(
                    'id',
                    $validated['device_id']
                )
                ->where(
                    'status',
                    'active'
                )
                ->first();


            if (!$device) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'device_id' =>
                            'Selected device does not belong to this user or is not active.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Device Limit Check
        |--------------------------------------------------------------------------
        */

        $activeDevices = $user
            ->devices()
            ->where(
                'status',
                'active'
            )
            ->count();


        if ($activeDevices > $plan->device_limit) {

            return back()
                ->withInput()
                ->withErrors([
                    'plan_id' =>
                        'User active devices exceed the selected plan device limit.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Subscription
        |--------------------------------------------------------------------------
        */

        $subscription = DB::transaction(
            function () use (
                $user,
                $plan,
                $device,
                $validated
            ) {

                $subscription = Subscription::create([

                    'user_id' =>
                        $user->id,

                    'subscription_plan_id' =>
                        $plan->id,

                    'device_id' =>
                        $device?->id,

                    'starts_at' =>
                        now(),

                    'expires_at' =>
                        now()->addDays(
                            $plan->duration_days
                        ),

                    'status' =>
                        'active',

                    'source' =>
                        'admin',

                    'auto_renew' =>
                        false,

                ]);


                $subscription->events()->create([

                    'event' =>
                        'admin_granted',

                    'old_status' =>
                        null,

                    'new_status' =>
                        'active',

                    'description' =>
                        'Subscription granted by admin. Reason: '
                        .$validated['reason'],

                ]);


                return $subscription;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Activate Protection
        |--------------------------------------------------------------------------
        */

        ProtectionActivationService::activate(
            $subscription->fresh()
        );


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        AdminActivityLogger::log(

            'ADMIN_SUBSCRIPTION_GRANTED',

            'Admin granted a subscription.',

            $request,

            $admin,

            AdminActivityLogger::INFO,

            [
                'subscription_id' =>
                    $subscription->id,

                'user_id' =>
                    $user->id,

                'plan_id' =>
                    $plan->id,

                'device_id' =>
                    $device?->id,

                'reason' =>
                    $validated['reason'],
            ]

        );


        return redirect()
            ->route(
                'admin.subscriptions.show',
                $subscription->id
            )
            ->with(
                'success',
                'Subscription granted successfully.'
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






/**
 * Check device management permission.
 */
private function ensureDevicePermission(
    Admin $admin
): void {

    if (
        $admin->role !== 'super_admin'
        &&
        !$admin->permissions->contains(
            'name',
            'manage_devices'
        )
    ) {
        abort(403);
    }
}

/**
 * Check subscription management permission.
 */
private function ensureSubscriptionPermission(
    Admin $admin
): void {

    if (
        $admin->role !== 'super_admin'
        &&
        !$admin->permissions->contains(
            'name',
            'manage_subscriptions'
        )
    ) {
        abort(403);
    }
}



}



