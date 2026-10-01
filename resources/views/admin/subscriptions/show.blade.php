@extends('layouts.admin')

@section('title', 'Subscription Details')
@section('page_title', 'Subscription Details')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4">

    <div>

        <a
            href="{{ route('admin.subscriptions') }}"
            class="text-decoration-none"
        >
            ← Back to Subscriptions
        </a>

        <h4 class="mt-3 mb-1">
            Subscription #{{ $subscription->id }}
        </h4>

        <div class="text-muted">
            {{ $subscription->user?->name ?? 'Unknown User' }}
        </div>

    </div>


    @if($subscription->status === 'active')

        <form
            method="POST"
            action="{{ route(
                'admin.subscriptions.cancel',
                $subscription->id
            ) }}"
            onsubmit="return confirm(
                'Cancel this subscription?'
            )"
        >

            @csrf

            <button
                class="btn btn-danger"
                type="submit"
            >
                Cancel Subscription
            </button>

        </form>

    @endif

</div>


<div class="row g-4">

    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Subscription
            </div>

            <div class="card-body">

                <table class="table">

                    <tr>
                        <th>Status</th>
                        <td>
                            {{ strtoupper(
                                $subscription->status
                            ) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Plan</th>
                        <td>
                            {{ $subscription->plan?->name ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Source</th>
                        <td>
                            {{ ucfirst(
                                $subscription->source
                            ) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Auto Renew</th>
                        <td>
                            {{ $subscription->auto_renew
                                ? 'Yes'
                                : 'No'
                            }}
                        </td>
                    </tr>

                    <tr>
                        <th>Starts At</th>
                        <td>
                            {{ $subscription->starts_at
                                ? $subscription
                                    ->starts_at
                                    ->format(
                                        'd M Y, h:i:s A'
                                    )
                                : '—'
                            }}
                        </td>
                    </tr>

                    <tr>
                        <th>Expires At</th>
                        <td>
                            {{ $subscription->expires_at
                                ? $subscription
                                    ->expires_at
                                    ->format(
                                        'd M Y, h:i:s A'
                                    )
                                : '—'
                            }}
                        </td>
                    </tr>

                    <tr>
                        <th>Payment Reference</th>
                        <td>
                            {{ $subscription
                                ->payment_reference
                                ?? '—'
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
                User / Device
            </div>

            <div class="card-body">

                <table class="table">

                    <tr>
                        <th>User</th>
                        <td>
                            {{ $subscription->user?->name ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>
                            {{ $subscription->user?->email ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Device</th>
                        <td>
                            {{ $subscription->device?->name
                                ?? '—'
                            }}
                        </td>
                    </tr>

                    <tr>
                        <th>Plan Price</th>

                        <td>

                            @if($subscription->plan)

                                {{ $subscription->plan->currency }}
                                {{ $subscription->plan->price }}

                            @else

                                —

                            @endif

                        </td>

                    </tr>

                    <tr>
                        <th>Duration</th>

                        <td>

                            {{ $subscription->plan
                                ? $subscription
                                    ->plan
                                    ->duration_days
                                    .' days'
                                : '—'
                            }}

                        </td>
                    </tr>

                </table>


                @if($subscription->user)

                    <a
                        href="{{ route(
                            'admin.users.show',
                            $subscription->user->id
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View User
                    </a>

                @endif


                @if($subscription->device)

                    <a
                        href="{{ route(
                            'admin.devices.show',
                            $subscription->device->id
                        ) }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        View Device
                    </a>

                @endif

            </div>

        </div>

    </div>

</div>


<div class="card mt-4">

    <div class="card-header fw-semibold">
        Subscription Events
    </div>

    <div class="table-responsive">

        <table class="table table-hover mb-0">

            <thead>

                <tr>
                    <th>Event</th>
                    <th>Old Status</th>
                    <th>New Status</th>
                    <th>Description</th>
                    <th>Time</th>
                </tr>

            </thead>

            <tbody>

            @forelse($subscription->events as $event)

                <tr>

                    <td>
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $event->event
                            )
                        ) }}
                    </td>

                    <td>
                        {{ $event->old_status ?? '—' }}
                    </td>

                    <td>
                        {{ $event->new_status ?? '—' }}
                    </td>

                    <td>
                        {{ $event->description ?? '—' }}
                    </td>

                    <td>

                        {{ $event->created_at
                            ? $event
                                ->created_at
                                ->format(
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
                        class="text-center text-muted py-4"
                    >
                        No subscription events recorded.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection