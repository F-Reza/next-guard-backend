@extends('layouts.admin')

@section('title', 'User Details')

@section('page_title', 'User Details')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3 mb-1">
            User Details
        </h1>

        <p class="text-muted mb-0">
            View account information.
        </p>

    </div>


    <div class="d-flex gap-2">

        <a
            href="{{ route('admin.users.edit', $user->id) }}"
            class="btn btn-primary"
        >
            <i class="bi bi-pencil me-1"></i>
            Edit
        </a>


        <form
            method="POST"
            action="{{ route('admin.users.destroy', $user->id) }}"
            onsubmit="return confirm('Are you sure you want to delete this user?');"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger"
            >
                <i class="bi bi-trash me-1"></i>
                Delete
            </button>

        </form>


        <a
            href="{{ route('admin.users') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>

</div>


<div class="row g-4">

    {{-- Account Information --}}

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="fw-semibold">
                    Account Information
                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            User ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $user->id }}
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Status
                        </div>

                        @if($user->status === 'active')

                            <span class="badge text-bg-success">
                                Active
                            </span>

                        @elseif($user->status === 'inactive')

                            <span class="badge text-bg-secondary">
                                Inactive
                            </span>

                        @else

                            <span class="badge text-bg-warning">
                                {{ ucfirst($user->status) }}
                            </span>

                        @endif

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Name
                        </div>

                        <div class="fw-semibold">
                            {{ $user->name }}
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Email
                        </div>

                        <div>

                            {{ $user->email ?: '—' }}

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Phone
                        </div>

                        <div>

                            {{ $user->phone ?: '—' }}

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Registered
                        </div>

                        <div>

                            {{ $user->created_at?->format('Y-m-d H:i:s') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Verification --}}

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="fw-semibold">
                    Verification
                </div>

            </div>


            <div class="card-body">


                <div class="d-flex justify-content-between align-items-center mb-3">

                    <span>
                        Email
                    </span>

                    @if($user->email_verified_at)

                        <span class="badge text-bg-success">
                            Verified
                        </span>

                    @else

                        <span class="badge text-bg-secondary">
                            Not Verified
                        </span>

                    @endif

                </div>


                <div class="d-flex justify-content-between align-items-center">

                    <span>
                        Phone
                    </span>

                    @if($user->phone_verified_at)

                        <span class="badge text-bg-success">
                            Verified
                        </span>

                    @else

                        <span class="badge text-bg-secondary">
                            Not Verified
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Timestamps --}}

    <div class="col-12">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="fw-semibold">
                    Account Timeline
                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div class="fw-semibold">

                            {{ $user->created_at?->format('Y-m-d H:i:s') }}

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="text-muted small">
                            Updated At
                        </div>

                        <div class="fw-semibold">

                            {{ $user->updated_at?->format('Y-m-d H:i:s') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection