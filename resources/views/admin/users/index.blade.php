@extends('layouts.admin')

@section('title', 'Users')

@section('page_title', 'Users')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Users
        </h1>

        <p class="text-muted mb-0">
            Manage registered users.
        </p>
    </div>


    <div class="d-flex align-items-center gap-2">

        <span class="badge text-bg-primary fs-6">
            {{ $users->total() }} Users
        </span>

        <a
            href="{{ route('admin.users.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-person-plus me-1"></i>
            Add User
        </a>

    </div>

</div>


{{-- Search / Filter --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.users') }}"
            class="row g-3"
        >

            <div class="col-md-6">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Name, email or phone"
                    value="{{ request('search') }}"
                >

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>

                </select>

            </div>


            <div class="col-md-3 d-flex align-items-end gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-search me-1"></i>
                    Search
                </button>


                <a
                    href="{{ route('admin.users') }}"
                    class="btn btn-outline-secondary"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

</div>


@if($users->isEmpty())

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i class="bi bi-people fs-1 text-muted"></i>

            <h5 class="mt-3">
                No users found
            </h5>

            <p class="text-muted">
                No users match your current search.
            </p>

            <a
                href="{{ route('admin.users.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-person-plus me-1"></i>
                Add First User
            </a>

        </div>

    </div>

@else

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="fw-semibold">
                Registered Users
            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4">
                            ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Registered
                        </th>

                        <th class="text-end px-4">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($users as $user)

                        <tr>

                            <td class="px-4 fw-semibold">
                                #{{ $user->id }}
                            </td>


                            <td>

                                <div class="fw-semibold">
                                    {{ $user->name }}
                                </div>

                            </td>


                            <td>
                                {{ $user->email ?: '—' }}
                            </td>


                            <td>
                                {{ $user->phone ?: '—' }}
                            </td>


                            <td>

                                @if($user->status === 'active')

                                    <span class="badge text-bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge text-bg-secondary">
                                        {{ ucfirst($user->status) }}
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="text-muted">

                                    {{ $user->created_at?->format('Y-m-d H:i') }}

                                </span>

                            </td>


                            <td class="text-end px-4">

                                <div class="btn-group">

                                    <a
                                        href="{{ route('admin.users.show', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    <a
                                        href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
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
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        @if($users->hasPages())

            <div class="card-footer bg-white">

                {{ $users->links() }}

            </div>

        @endif

    </div>

@endif

@endsection