@extends('layouts.admin')

@section('title', 'Device Details')
@section('page_title', 'Device Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <a
            href="{{ route('admin.devices') }}"
            class="text-decoration-none"
        >
            ← Back to Devices
        </a>

        <h4 class="mt-2 mb-1">

            {{ $device->name ?: 'Unnamed Device' }}

        </h4>

        <div class="text-muted">
            Device ID: {{ $device->id }}
        </div>

    </div>


    <div class="d-flex gap-2">

        @if($device->status === 'active')

            <form
                method="POST"
                action="{{ route(
                    'admin.devices.revoke',
                    $device->id
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to revoke this device?'
                )"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Revoke Device
                </button>

            </form>

        @elseif($device->status === 'revoked')

            <form
                method="POST"
                action="{{ route(
                    'admin.devices.reactivate',
                    $device->id
                ) }}"
                onsubmit="return confirm(
                    'Reactivate this device?'
                )"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Reactivate Device
                </button>

            </form>

        @endif

    </div>

</div>


<div class="row g-4">

    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Device Information
            </div>

            <div class="card-body">

                <table class="table">

                    <tr>
                        <th>Name</th>
                        <td>
                            {{ $device->name ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Platform</th>
                        <td>
                            {{ $device->platform }}
                        </td>
                    </tr>

                    <tr>
                        <th>Manufacturer</th>
                        <td>
                            {{ $device->manufacturer ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Model</th>
                        <td>
                            {{ $device->model ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Android Version</th>
                        <td>
                            {{ $device->android_version ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>App Version</th>
                        <td>
                            {{ $device->app_version ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Management Mode</th>
                        <td>
                            {{ strtoupper(
                                $device->management_mode
                            ) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            {{ strtoupper(
                                $device->status
                            ) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Last Seen</th>

                        <td>

                            {{ $device->last_seen_at
                                ? $device
                                    ->last_seen_at
                                    ->format('d M Y, h:i:s A')
                                : 'Never'
                            }}

                        </td>
                    </tr>

                </table>

            </div>

        </div>

    </div>


    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Owner
            </div>

            <div class="card-body">

                @if($device->user)

                    <table class="table">

                        <tr>
                            <th>Name</th>
                            <td>
                                {{ $device->user->name }}
                            </td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>
                                {{ $device->user->email ?? '—' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Phone</th>
                            <td>
                                {{ $device->user->phone ?? '—' }}
                            </td>
                        </tr>

                    </table>

                    <a
                        href="{{ route(
                            'admin.users.show',
                            $device->user->id
                        ) }}"
                        class="btn btn-outline-primary"
                    >
                        View User
                    </a>

                @else

                    <div class="text-muted">
                        No owner assigned.
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


<div class="card mt-4">

    <div class="card-header fw-semibold">
        Protection Status
    </div>

    <div class="card-body">

        @if($device->protectionSetting)

            @php
                $p = $device->protectionSetting;
            @endphp

            <div class="row g-3">

                <div class="col-md-4">
                    <strong>Status:</strong>
                    {{ strtoupper(
                        $p->protection_status
                    ) }}
                </div>

                <div class="col-md-4">
                    <strong>Betting Block:</strong>
                    {{ $p->betting_block ? 'Yes' : 'No' }}
                </div>

                <div class="col-md-4">
                    <strong>Adult Block:</strong>
                    {{ $p->adult_content_block ? 'Yes' : 'No' }}
                </div>

                <div class="col-md-4">
                    <strong>Safe Search:</strong>
                    {{ $p->safe_search ? 'Yes' : 'No' }}
                </div>

                <div class="col-md-4">
                    <strong>DNS Protection:</strong>
                    {{ $p->dns_protection ? 'Yes' : 'No' }}
                </div>

                <div class="col-md-4">
                    <strong>Last Sync:</strong>

                    {{ $p->last_sync_at
                        ? $p
                            ->last_sync_at
                            ->format('d M Y, h:i:s A')
                        : 'Never'
                    }}

                </div>

            </div>


            @if($p->disabled_reason)

                <div class="alert alert-warning mt-3 mb-0">

                    <strong>
                        Disabled Reason:
                    </strong>

                    {{ $p->disabled_reason }}

                </div>

            @endif

        @else

            <div class="text-muted">
                Protection settings not configured.
            </div>

        @endif

    </div>

</div>


<div class="card mt-4">

    <div class="card-header fw-semibold">
        Sessions
    </div>

    <div class="table-responsive">

        <table class="table mb-0">

            <thead>

            <tr>
                <th>Status</th>
                <th>IP Address</th>
                <th>Last Used</th>
                <th>Expires</th>
                <th>Revoked</th>
            </tr>

            </thead>

            <tbody>

            @forelse($device->deviceSessions as $session)

                <tr>

                    <td>
                        {{ strtoupper(
                            $session->status
                        ) }}
                    </td>

                    <td>
                        {{ $session->ip_address ?? '—' }}
                    </td>

                    <td>

                        {{ $session->last_used_at
                            ? $session
                                ->last_used_at
                                ->format('d M Y, h:i:s A')
                            : '—'
                        }}

                    </td>

                    <td>

                        {{ $session->expires_at
                            ? $session
                                ->expires_at
                                ->format('d M Y, h:i:s A')
                            : '—'
                        }}

                    </td>

                    <td>

                        {{ $session->revoked_at
                            ? $session
                                ->revoked_at
                                ->format('d M Y, h:i:s A')
                            : '—'
                        }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="text-center text-muted py-4"
                    >
                        No sessions found.
                    </td>

                </tr>

            @endforelse
            

            </tbody>

        </table>

    </div>


    @if($device->deviceSessions->count() >= 5)

        <div class="card-footer text-end">

            <a
                href="{{ route(
                    'admin.devices.sessions',
                    $device->id
                ) }}"
                class="btn btn-sm btn-outline-secondary"
            >
                View All Sessions
            </a>

        </div>

    @endif


</div>




<div class="card mt-4">

    <div class="card-header fw-semibold">
        Recent Device Events
    </div>

    <div class="table-responsive">

        <table class="table mb-0">

            <thead>
            <tr>
                <th>Event</th>
                <th>Time</th>
            </tr>
            </thead>

            <tbody>

            @forelse($device->events as $event)

                <tr>

                    <td>
                        {{ $event->event }}
                    </td>

                    <td>

                        {{ $event->created_at
                            ? \Illuminate\Support\Carbon::parse(
                                $event->created_at
                            )->format('d M Y, h:i:s A')
                            : '—'
                        }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="2"
                        class="text-center text-muted py-4"
                    >
                        No device events recorded.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($device->events->count() >= 10)

        <div class="card-footer text-end">

            <a
                href="{{ route(
                    'admin.devices.events',
                    $device->id
                ) }}"
                class="btn btn-sm btn-outline-secondary"
            >
                View All Events
            </a>

        </div>

    @endif

</div>

@endsection