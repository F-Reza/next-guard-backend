@extends('layouts.admin')

@section('title', 'Reactivate Subscription')
@section('page_title', 'Reactivate Subscription')

@section('content')

<div class="mb-4">

    <a
        href="{{ route(
            'admin.subscriptions.show',
            $subscription->id
        ) }}"
        class="text-decoration-none"
    >
        ← Back to Subscription
    </a>


    <h4 class="mt-3 mb-1">
        Reactivate Subscription
    </h4>


    <div class="text-muted">

        Subscription #{{ $subscription->id }}

        ·

        {{ $subscription->user?->name ?? 'Unknown User' }}

    </div>

</div>


@if($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="row g-4">

    {{-- Current Subscription --}}
    <div class="col-lg-5">

        <div class="card">

            <div class="card-header fw-semibold">
                Subscription Information
            </div>

            <div class="card-body">

                <table class="table mb-0">

                    <tr>

                        <th>
                            Status
                        </th>

                        <td>
                            <span class="badge text-bg-warning">
                                Cancelled
                            </span>
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
                            User
                        </th>

                        <td>
                            {{ $subscription->user?->name ?? '—' }}
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
                            Starts At
                        </th>

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

                        <th>
                            Expires At
                        </th>

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

                </table>

            </div>

        </div>

    </div>


    {{-- Reactivation Form --}}
    <div class="col-lg-7">

        <div class="card">

            <div class="card-header fw-semibold">
                Reactivation
            </div>

            <div class="card-body">

                @if(
                    !$subscription->expires_at
                    ||
                    $subscription->expires_at->isPast()
                )

                    <div class="alert alert-danger mb-0">

                        This subscription has already expired
                        and cannot be reactivated.

                        Use Admin Grant to create a new
                        subscription.

                    </div>

                @else

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.subscriptions.reactivate.store',
                            $subscription->id
                        ) }}"
                    >

                        @csrf


                        <div class="alert alert-info">

                            <strong>
                                Remaining entitlement:
                            </strong>

                            This subscription will keep its
                            existing expiry date:

                            <strong>
                                {{ $subscription
                                    ->expires_at
                                    ->format(
                                        'd M Y, h:i:s A'
                                    )
                                }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Reactivation Reason
                            </label>

                            <textarea
                                name="reason"
                                rows="4"
                                class="form-control
                                    @error('reason')
                                    is-invalid
                                    @enderror"
                                placeholder="Enter reason for reactivation..."
                                required
                            >{{ old('reason') }}</textarea>


                            @error('reason')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="alert alert-warning">

                            Reactivation will change the
                            subscription status from

                            <strong>
                                Cancelled
                            </strong>

                            to

                            <strong>
                                Active
                            </strong>.

                            The existing expiry date will
                            not be extended.

                        </div>


                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Reactivate this subscription?'
                                )"
                            >
                                Reactivate Subscription
                            </button>


                            <a
                                href="{{ route(
                                    'admin.subscriptions.show',
                                    $subscription->id
                                ) }}"
                                class="btn btn-outline-secondary"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection