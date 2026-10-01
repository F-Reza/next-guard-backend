@extends('layouts.admin')

@section('title', 'Change Subscription Plan')
@section('page_title', 'Change Subscription Plan')

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
        Change Plan
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

    <div class="col-lg-5">

        <div class="card">

            <div class="card-header fw-semibold">
                Current Subscription
            </div>

            <div class="card-body">

                <table class="table mb-0">

                    <tr>

                        <th>
                            Current Plan
                        </th>

                        <td>
                            {{ $subscription->plan?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Price
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
                            Device Limit
                        </th>

                        <td>
                            {{ $subscription->plan?->device_limit ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Expires
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

                </table>

            </div>

        </div>

    </div>


    <div class="col-lg-7">

        <div class="card">

            <div class="card-header fw-semibold">
                Select New Plan
            </div>

            <div class="card-body">

                @if($plans->isEmpty())

                    <div class="alert alert-warning mb-0">
                        No other active plans are available.
                    </div>

                @else

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.subscriptions.change-plan.store',
                            $subscription->id
                        ) }}"
                    >

                        @csrf


                        <div class="mb-3">

                            <label class="form-label">
                                New Plan
                            </label>

                            <select
                                name="plan_id"
                                class="form-select
                                    @error('plan_id')
                                    is-invalid
                                    @enderror"
                                required
                            >

                                <option value="">
                                    Select a plan
                                </option>


                                @foreach($plans as $plan)

                                    <option
                                        value="{{ $plan->id }}"
                                        @selected(
                                            old('plan_id')
                                            ==
                                            $plan->id
                                        )
                                    >

                                        {{ $plan->name }}

                                        —

                                        {{ $plan->currency }}

                                        {{ number_format(
                                            (float) $plan->price,
                                            2
                                        ) }}

                                        —

                                        {{ $plan->duration_days }}
                                        days

                                        —

                                        {{ $plan->device_limit }}
                                        device(s)

                                    </option>

                                @endforeach

                            </select>


                            @error('plan_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="alert alert-warning">

                            <strong>
                                Important:
                            </strong>

                            The current subscription will be marked
                            as Changed and a new active subscription
                            will be created using the selected plan.

                        </div>


                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Change this subscription plan?'
                                )"
                            >
                                Confirm Plan Change
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