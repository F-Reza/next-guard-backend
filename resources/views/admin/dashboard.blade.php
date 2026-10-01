@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')


@section('content')

<div class="mb-4">

    <h1 class="h3 mb-1">
        Dashboard
    </h1>

    <p class="text-muted mb-0">
        Welcome back, {{ $admin->name }}.
    </p>

</div>


<div class="row g-4">


    {{-- Users --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Users
                        </div>

                        <div class="fs-3 fw-bold mt-1">
                            {{ number_format($stats['users']) }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Devices --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Devices
                        </div>

                        <div class="fs-3 fw-bold mt-1">
                            {{ number_format($stats['devices']) }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-phone"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Subscriptions --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Subscriptions
                        </div>

                        <div class="fs-3 fw-bold mt-1">
                            {{ number_format($stats['subscriptions']) }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Payments --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Payments
                        </div>

                        <div class="fs-3 fw-bold mt-1">
                            {{ number_format($stats['payments']) }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


</div>


<div class="row g-4 mt-1">


    <div class="col-12 col-lg-4">

        <div class="card stat-card">

            <div class="card-body">

                <div class="text-muted small">
                    Subscription Plans
                </div>

                <div class="fs-4 fw-bold">
                    {{ number_format($stats['plans']) }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-lg-4">

        <div class="card stat-card">

            <div class="card-body">

                <div class="text-muted small">
                    License Codes
                </div>

                <div class="fs-4 fw-bold">
                    {{ number_format($stats['licenses']) }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-lg-4">

        <div class="card stat-card">

            <div class="card-body">

                <div class="text-muted small">
                    Unread Notifications
                </div>

                <div class="fs-4 fw-bold">
                    {{ number_format($stats['unread_notifications']) }}
                </div>

            </div>

        </div>

    </div>


</div>


<div class="card stat-card mt-4">

    <div class="card-body">

        <h5 class="card-title">
            Admin Account
        </h5>

        <div class="row mt-3">

            <div class="col-md-4 mb-3">

                <div class="text-muted small">
                    Name
                </div>

                <div class="fw-semibold">
                    {{ $admin->name }}
                </div>

            </div>


            <div class="col-md-4 mb-3">

                <div class="text-muted small">
                    Email
                </div>

                <div class="fw-semibold">
                    {{ $admin->email }}
                </div>

            </div>


            <div class="col-md-4 mb-3">

                <div class="text-muted small">
                    Role
                </div>

                <span class="badge text-bg-primary">
                    {{ $admin->role }}
                </span>

            </div>

        </div>

    </div>

</div>

@endsection