@extends('layouts.admin')

@section('title', 'Edit User')

@section('page_title', 'Edit User')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3 mb-1">
            Edit User
        </h1>

        <p class="text-muted mb-0">
            Update user account information.
        </p>

    </div>


    <a
        href="{{ route('admin.users.show', $user->id) }}"
        class="btn btn-outline-secondary"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back
    </a>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <form
            method="POST"
            action="{{ route('admin.users.update', $user->id) }}"
        >

            @csrf

            @method('PUT')


            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        required
                        maxlength="120"
                    >

                    @error('name')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        maxlength="190"
                    >

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $user->phone) }}"
                        maxlength="30"
                    >

                    @error('phone')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option
                            value="active"
                            @selected(old('status', $user->status) === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            @selected(old('status', $user->status) === 'inactive')
                        >
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-12">

                    <div class="alert alert-light border mb-0">

                        <i class="bi bi-info-circle me-1"></i>

                        Leave password fields empty to keep the current password.

                    </div>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        minlength="8"
                        autocomplete="new-password"
                    >

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Confirm New Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        minlength="8"
                        autocomplete="new-password"
                    >

                </div>


                <div class="col-12">

                    <hr>

                    <div class="d-flex justify-content-end gap-2">

                        <a
                            href="{{ route('admin.users.show', $user->id) }}"
                            class="btn btn-outline-secondary"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Save Changes
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection