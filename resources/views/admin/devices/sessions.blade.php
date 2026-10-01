@extends('layouts.admin')

@section('title', 'Device Sessions')
@section('page_title', 'Device Sessions')

@section('content')

<div class="mb-4">

    <a
        href="{{ route(
            'admin.devices.show',
            $device->id
        ) }}"
        class="text-decoration-none"
    >
        ← Back to Device
    </a>

    <div class="mt-3">

        <h4 class="mb-1">
            Device Sessions
        </h4>

        <div class="text-muted">
            {{ $device->name ?: 'Unnamed Device' }}
            · Device ID: {{ $device->id }}
        </div>

    </div>

</div>


<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <span class="fw-semibold">
            Session History
        </span>

        <span class="badge text-bg-secondary">
            {{ $sessions->total() }} Sessions
        </span>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

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

                @forelse($sessions as $session)

                    <tr>

                        <td>

                            @if($session->status === 'active')

                                <span class="badge text-bg-success">
                                    Active
                                </span>

                            @elseif($session->status === 'revoked')

                                <span class="badge text-bg-danger">
                                    Revoked
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($session->status) }}
                                </span>

                            @endif

                        </td>


                        <td>
                            {{ $session->ip_address ?? '—' }}
                        </td>


                        <td>

                            {{ $session->last_used_at
                                ? $session->last_used_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>


                        <td>

                            {{ $session->expires_at
                                ? $session->expires_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>


                        <td>

                            {{ $session->revoked_at
                                ? $session->revoked_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-muted py-5"
                        >
                            No sessions found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($sessions->hasPages())

        <div class="card-footer">
            {{ $sessions->links() }}
        </div>

    @endif

</div>


@endsection