<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminWebAuth
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $guard = Auth::guard('admin_web');

        if (!$guard->check()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login to continue.');
        }

        $admin = $guard->user();

        if (!$admin || $admin->status !== 'active') {

            $guard->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('error', 'Admin account is inactive.');
        }

        return $next($request);
    }
}