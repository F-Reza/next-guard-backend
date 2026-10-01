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


    <div class="d-flex gap-2">

        @if($subscription->status === 'active')

            <a
                href="{{ route(
                    'admin.subscriptions.change-plan',
                    $subscription->id
                ) }}"
                class="btn btn-primary"
            >
                Change Plan
            </a>

        @endif
        
        @if($subscription->status === 'active')

            <form
                method="POST"
                action="{{ route(
                    'admin.subscriptions.cancel',
                    $subscription->id
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to cancel this subscription?'
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

</div>


<div class="row g-4">

    {{-- Subscription Information --}}
    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Subscription Information
            </div>

            <div class="card-body">

                <table class="table align-middle mb-0">

                    <tr>

                        <th style="width: 40%;">
                            Status
                        </th>

                        <td>

                            @if($subscription->status === 'active')

                                <span class="badge text-bg-success">
                                    Active
                                </span>

                            @elseif($subscription->status === 'cancelled')

                                <span class="badge text-bg-warning">
                                    Cancelled
                                </span>

                            @elseif($subscription->status === 'expired')

                                <span class="badge text-bg-secondary">
                                    Expired
                                </span>

                            @elseif($subscription->status === 'changed')

                                <span class="badge text-bg-info">
                                    Changed
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($subscription->status) }}
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Plan
                        </th>

                        <td>
                            {{ $subscription->plan?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Source
                        </th>

                        <td>

                            @if($subscription->source === 'payment')

                                <span class="badge text-bg-primary">
                                    Payment
                                </span>

                            @elseif($subscription->source === 'license')

                                <span class="badge text-bg-secondary">
                                    License
                                </span>

                            @elseif($subscription->source === 'admin')

                                <span class="badge text-bg-dark">
                                    Admin
                                </span>

                            @elseif($subscription->source === 'upgrade')

                                <span class="badge text-bg-info">
                                    Upgrade
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($subscription->source) }}
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Auto Renew
                        </th>

                        <td>

                            @if($subscription->auto_renew)

                                <span class="badge text-bg-success">
                                    Yes
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    No
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Starts At
                        </th>

                        <td>

                            {{ $subscription->starts_at
                                ? $subscription
                                    ->starts_at
                                    ->format('d M Y, h:i:s A')
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Expires At
                        </th>

                        <td>

                            {{ $subscription->expires_at
                                ? $subscription
                                    ->expires_at
                                    ->format('d M Y, h:i:s A')
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Payment Reference
                        </th>

                        <td>

                            {{ $subscription->payment_reference
                                ?? '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Created At
                        </th>

                        <td>

                            {{ $subscription->created_at
                                ? $subscription
                                    ->created_at
                                    ->format('d M Y, h:i:s A')
                                : '—'
                            }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>


    {{-- User / Device --}}
    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                User / Device
            </div>

            <div class="card-body">

                <table class="table align-middle mb-3">

                    <tr>

                        <th style="width: 40%;">
                            User
                        </th>

                        <td>
                            {{ $subscription->user?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Email
                        </th>

                        <td>
                            {{ $subscription->user?->email ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Phone
                        </th>

                        <td>
                            {{ $subscription->user?->phone ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Device
                        </th>

                        <td>

                            @if($subscription->device)

                                {{ $subscription->device->name
                                    ?: 'Device #'.$subscription->device->id
                                }}

                            @else

                                —

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Plan Price
                        </th>

                        <td>

                            @if($subscription->plan)

                                {{ $subscription->plan->currency }}
                                {{ number_format(
                                    (float) $subscription->plan->price,
                                    2
                                ) }}

                            @else

                                —

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Duration
                        </th>

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


                    <tr>

                        <th>
                            Device Limit
                        </th>

                        <td>

                            {{ $subscription->plan
                                ? $subscription
                                    ->plan
                                    ->device_limit
                                : '—'
                            }}

                        </td>

                    </tr>

                </table>


                <div class="d-flex gap-2 flex-wrap">

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

</div>


{{-- Subscription Events --}}
<div class="card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <span class="fw-semibold">
            Subscription Events
        </span>

        <span class="badge text-bg-secondary">
            {{ $subscription->events->count() }} Events
        </span>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead>

                <tr>

                    <th>
                        Event
                    </th>

                    <th>
                        Old Status
                    </th>

                    <th>
                        New Status
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Time
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($subscription->events as $event)

                    <tr>

                        <td>

                            <span class="fw-semibold">

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $event->event
                                    )
                                ) }}

                            </span>

                        </td>


                        <td>

                            @if($event->old_status)

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($event->old_status) }}
                                </span>

                            @else

                                —

                            @endif

                        </td>


                        <td>

                            @if($event->new_status === 'active')

                                <span class="badge text-bg-success">
                                    Active
                                </span>

                            @elseif($event->new_status === 'cancelled')

                                <span class="badge text-bg-warning">
                                    Cancelled
                                </span>

                            @elseif($event->new_status === 'expired')

                                <span class="badge text-bg-secondary">
                                    Expired
                                </span>

                            @elseif($event->new_status === 'changed')

                                <span class="badge text-bg-info">
                                    Changed
                                </span>

                            @elseif($event->new_status)

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($event->new_status) }}
                                </span>

                            @else

                                —

                            @endif

                        </td>


                        <td>
                            {{ $event->description ?? '—' }}
                        </td>


                        <td>

                            {{ $event->created_at
                                ? $event
                                    ->created_at
                                    ->format('d M Y, h:i:s A')
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
                            No subscription events recorded.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection