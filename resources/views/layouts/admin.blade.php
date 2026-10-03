<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', 'Admin Panel') - Next Guard
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body>

<aside
    class="admin-sidebar offcanvas-lg offcanvas-start"
    tabindex="-1"
    id="adminSidebar"
>

    <div class="admin-brand">

        <div>

            <div class="admin-brand-title">
                Next Guard
            </div>

            <div class="admin-brand-subtitle">
                ADMIN CONTROL PANEL
            </div>

        </div>

    </div>


    <div class="offcanvas-body">

        <nav class="admin-nav">

            <div class="small text-uppercase text-secondary px-3 mb-2">
                Main
            </div>


            <a
                href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>


            @if(
                $admin->role === 'super_admin'
                ||
                $admin->permissions->contains('name', 'manage_users')
            )

                <a
                    href="{{ route('admin.users') }}"
                    class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                >
                    <i class="bi bi-people"></i>
                    Users
                </a>

            @endif


            @if(
                $admin->role === 'super_admin'
                ||
                $admin->permissions->contains(
                    'name',
                    'manage_devices'
                )
            )

                <a
                    href="{{ route('admin.devices') }}"
                    class="nav-link {{ request()->routeIs('admin.devices*') ? 'active' : '' }}"
                >
                    <i class="bi bi-phone"></i>
                    Devices
                </a>

            @endif           


            @if(
                $admin->role === 'super_admin'
                ||
                $admin->permissions->contains('name', 'manage_plans')
            )

                <a
                    href="{{ route('admin.plans') }}"
                    class="nav-link
                        {{ request()->routeIs('admin.plans*')
                            ? 'active'
                            : ''
                        }}"
                >
                    <i class="bi bi-box-seam"></i>
                    Plans
                </a>

            @endif


            @if(
                $admin->role === 'super_admin'
                ||
                $admin->permissions->contains('name', 'manage_subscriptions')
            )

            <a
                href="{{ route('admin.subscriptions') }}"
                class="nav-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}"
            >
                <i class="bi bi-credit-card"></i>
                Subscriptions
            </a>

            @endif


            @if(
                $admin->role === 'super_admin'
                ||
                $admin->permissions->contains('name', 'manage_licenses')
            )

                <a href="#" class="nav-link">
                    <i class="bi bi-key"></i>
                    Licenses
                </a>

            @endif


            <div class="small text-uppercase text-secondary px-3 mt-4 mb-2">
                Security
            </div>


            @if(
                $admin->role === 'super_admin'
                ||
                $admin->permissions->contains('name', 'view_logs')
            )

                <a href="#" class="nav-link">
                    <i class="bi bi-journal-text"></i>
                    Activity Logs
                </a>

            @endif


            @if($admin->role === 'super_admin')

                <a href="#" class="nav-link">
                    <i class="bi bi-shield-lock"></i>
                    Admin Management
                </a>

            @endif


            <a href="#" class="nav-link">
                <i class="bi bi-bell"></i>
                Notifications
            </a>

        </nav>

    </div>

</aside>


<div class="admin-main">

    <nav class="admin-topbar navbar px-3 px-lg-4">

        <button
            class="btn btn-outline-secondary d-lg-none me-2"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#adminSidebar"
        >
            <i class="bi bi-list"></i>
        </button>


        <span class="navbar-brand fw-semibold mb-0">
            @yield('page_title', 'Dashboard')
        </span>


        <div class="ms-auto dropdown">

            <button
                class="btn btn-light dropdown-toggle"
                data-bs-toggle="dropdown"
                type="button"
            >

                <i class="bi bi-person-circle me-1"></i>

                {{ $admin->name }}

            </button>


            <ul class="dropdown-menu dropdown-menu-end">

                <li>
                    <span class="dropdown-item-text">

                        <strong>
                            {{ $admin->name }}
                        </strong>

                        <br>

                        <small class="text-muted">
                            {{ $admin->role }}
                        </small>

                    </span>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <form
                        method="POST"
                        action="{{ route('admin.logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item text-danger"
                        >
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </nav>


    <main class="container-fluid p-3 p-lg-4">

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @yield('content')

    </main>

</div>

</body>

</html>