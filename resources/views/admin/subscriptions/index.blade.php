@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('page_title', 'Subscriptions')

@section('content')

<div class="mb-4">

    <h4 class="mb-1">
        Subscription Management
    </h4>

    <div class="text-muted">
        View and manage user subscriptions.
    </div>

</div>


<div class="card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.subscriptions') }}"
            class="row g-3"
        >

            <div class="col-lg-4">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="User, email, ID, reference..."
                >

            </div>


            <div class="col-lg-2">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    <option
                        value="active"
                        @selected(
                            request('status') === 'active'
                        )
                    >
                        Active
                    </option>

                    <option
                        value="cancelled"
                        @selected(
                            request('status') === 'cancelled'
                        )
                    >
                        Cancelled
                    </option>

                    <option
                        value="expired"
                        @selected(
                            request('status') === 'expired'
                        )
                    >
                        Expired
                    </option>

                </select>

            </div>


            <div class="col-lg-2">

                <label class="form-label">
                    Plan
                </label>

                <select
                    name="plan_id"
                    class="form-select"
                >

                    <option value="">
                        All Plans
                    </option>

                    @foreach($plans as $plan)

                        <option
                            value="{{ $plan->id }}"
                            @selected(
                                (string) request('plan_id')
                                ===
                                (string) $plan->id
                            )
                        >
                            {{ $plan->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-lg-2">

                <label class="form-label">
                    Source
                </label>

                <select
                    name="source"
                    class="form-select"
                >

                    <option value="">
                        All Sources
                    </option>

                    <option
                        value="payment"
                        @selected(
                            request('source') === 'payment'
                        )
                    >
                        Payment
                    </option>

                    <option
                        value="license"
                        @selected(
                            request('source') === 'license'
                        )
                    >
                        License
                    </option>

                    <option
                        value="admin"
                        @selected(
                            request('source') === 'admin'
                        )
                    >
                        Admin
                    </option>

                </select>

            </div>


            <div class="col-lg-2 d-flex align-items-end">

                <button
                    type="submit"
                    class="btn btn-primary w-100"
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
                    <th>ID</th>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Device</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Starts</th>
                    <th>Expires</th>
                    <th></th>
                </tr>

            </thead>

            <tbody>

            @forelse($subscriptions as $subscription)

                <tr>

                    <td>
                        #{{ $subscription->id }}
                    </td>


                    <td>

                        @if($subscription->user)

                            <div class="fw-semibold">
                                {{ $subscription->user->name }}
                            </div>

                            <small class="text-muted">
                                {{ $subscription->user->email }}
                            </small>

                        @else

                            —
                        @endif

                    </td>


                    <td>
                        {{ $subscription->plan?->name ?? '—' }}
                    </td>


                    <td>

                        @if($subscription->device)

                            {{ $subscription->device->name
                                ?: 'Device #'.$subscription->device->id
                            }}

                        @else

                            <span class="text-muted">
                                —
                            </span>

                        @endif

                    </td>


                    <td>

                        <span class="badge text-bg-secondary">

                            {{ ucfirst(
                                $subscription->source
                            ) }}

                        </span>

                    </td>


                    <td>

                        @if($subscription->status === 'active')

                            <span class="badge text-bg-success">
                                Active
                            </span>

                        @elseif(
                            $subscription->status === 'cancelled'
                        )

                            <span class="badge text-bg-warning">
                                Cancelled
                            </span>

                        @elseif(
                            $subscription->status === 'expired'
                        )

                            <span class="badge text-bg-secondary">
                                Expired
                            </span>

                        @else

                            <span class="badge text-bg-secondary">
                                {{ ucfirst(
                                    $subscription->status
                                ) }}
                            </span>

                        @endif

                    </td>


                    <td>

                        {{ $subscription->starts_at
                            ? $subscription
                                ->starts_at
                                ->format(
                                    'd M Y, h:i A'
                                )
                            : '—'
                        }}

                    </td>


                    <td>

                        {{ $subscription->expires_at
                            ? $subscription
                                ->expires_at
                                ->format(
                                    'd M Y, h:i A'
                                )
                            : '—'
                        }}

                    </td>


                    <td class="text-end">

                        <a
                            href="{{ route(
                                'admin.subscriptions.show',
                                $subscription->id
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
                        colspan="9"
                        class="text-center text-muted py-5"
                    >
                        No subscriptions found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($subscriptions->hasPages())

        <div class="card-footer">

            {{ $subscriptions->links() }}

        </div>

    @endif

</div>

@endsection