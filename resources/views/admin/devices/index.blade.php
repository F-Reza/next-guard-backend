@extends('layouts.admin')

@section('title', 'Devices')
@section('page_title', 'Devices')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            Device Management
        </h4>

        <div class="text-muted">
            View and manage enrolled devices.
        </div>
    </div>

</div>


<div class="card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.devices') }}"
            class="row g-3"
        >

            <div class="col-lg-5">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Device, user, email, model..."
                >

            </div>


            <div class="col-lg-3">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >

                    <option value="">
                        All statuses
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="revoked"
                        @selected(request('status') === 'revoked')
                    >
                        Revoked
                    </option>

                </select>

            </div>


            <div class="col-lg-2">

                <label class="form-label">
                    Mode
                </label>

                <select
                    name="management_mode"
                    class="form-select"
                >

                    <option value="">
                        All modes
                    </option>

                    <option
                        value="standard"
                        @selected(
                            request('management_mode')
                            ===
                            'standard'
                        )
                    >
                        Standard
                    </option>

                    <option
                        value="managed"
                        @selected(
                            request('management_mode')
                            ===
                            'managed'
                        )
                    >
                        Managed
                    </option>

                </select>

            </div>


            <div class="col-lg-2 d-flex align-items-end">

                <button
                    class="btn btn-primary w-100"
                    type="submit"
                >
                    Filter
                </button>

            </div>

        </form>

    </div>

</div>


<div class="card">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead>

            <tr>
                <th>Device</th>
                <th>Owner</th>
                <th>Model</th>
                <th>Mode</th>
                <th>Protection</th>
                <th>Status</th>
                <th>Last Seen</th>
                <th></th>
            </tr>

            </thead>

            <tbody>

            @forelse($devices as $device)

                <tr>

                    <td>

                        <div class="fw-semibold">

                            {{ $device->name ?: 'Unnamed Device' }}

                        </div>

                        <small class="text-muted">

                            ID: {{ $device->id }}

                        </small>

                    </td>


                    <td>

                        @if($device->user)

                            <div class="fw-semibold">
                                {{ $device->user->name }}
                            </div>

                            <small class="text-muted">
                                {{ $device->user->email }}
                            </small>

                        @else

                            <span class="text-muted">
                                No owner
                            </span>

                        @endif

                    </td>


                    <td>

                        {{ $device->manufacturer ?? '—' }}

                        {{ $device->model ?? '' }}

                        <div class="small text-muted">

                            Android
                            {{ $device->android_version ?? '—' }}

                        </div>

                    </td>


                    <td>

                        <span class="badge text-bg-secondary">

                            {{ strtoupper(
                                $device->management_mode
                            ) }}

                        </span>

                    </td>


                    <td>

                        @php
                            $protection =
                                $device->protectionSetting;
                        @endphp

                        @if($protection)

                            @if(
                                $protection->protection_status
                                ===
                                'active'
                            )

                                <span class="badge text-bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge text-bg-secondary">

                                    {{ ucfirst(
                                        $protection
                                            ->protection_status
                                    ) }}

                                </span>

                            @endif

                        @else

                            <span class="text-muted">
                                Not configured
                            </span>

                        @endif

                    </td>


                    <td>

                        @if($device->status === 'active')

                            <span class="badge text-bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge text-bg-danger">

                                {{ ucfirst($device->status) }}

                            </span>

                        @endif

                    </td>


                    <td>

                        @if($device->last_seen_at)

                            {{ $device
                                ->last_seen_at
                                ->diffForHumans() }}

                        @else

                            <span class="text-muted">
                                Never
                            </span>

                        @endif

                    </td>


                    <td class="text-end">

                        <a
                            href="{{ route(
                                'admin.devices.show',
                                $device->id
                            ) }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center py-5 text-muted"
                    >
                        No devices found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($devices->hasPages())

        <div class="card-footer">

            {{ $devices->links() }}

        </div>

    @endif

</div>

@endsection