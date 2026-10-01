@extends('layouts.admin')

@section('title', 'Extend Subscription')
@section('page_title', 'Extend Subscription')

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
        Extend Subscription
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
                            Plan
                        </th>

                        <td>
                            {{ $subscription->plan?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Status
                        </th>

                        <td>

                            <span class="badge text-bg-success">
                                {{ ucfirst($subscription->status) }}
                            </span>

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Current Expiry
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

                </table>

            </div>

        </div>

    </div>


    <div class="col-lg-7">

        <div class="card">

            <div class="card-header fw-semibold">
                Extension Details
            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.subscriptions.extend.store',
                        $subscription->id
                    ) }}"
                >

                    @csrf


                    <div class="mb-3">

                        <label class="form-label">
                            Preset Extension
                        </label>

                        <select
                            name="preset_days"
                            class="form-select"
                        >

                            <option value="">
                                Select preset
                            </option>

                            <option
                                value="7"
                                @selected(
                                    old('preset_days') == 7
                                )
                            >
                                7 Days
                            </option>

                            <option
                                value="30"
                                @selected(
                                    old('preset_days') == 30
                                )
                            >
                                30 Days
                            </option>

                            <option
                                value="90"
                                @selected(
                                    old('preset_days') == 90
                                )
                            >
                                90 Days
                            </option>

                            <option
                                value="365"
                                @selected(
                                    old('preset_days') == 365
                                )
                            >
                                365 Days
                            </option>

                        </select>

                        <div class="form-text">
                            Use either preset days or custom days.
                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Custom Days
                        </label>

                        <input
                            type="number"
                            name="custom_days"
                            value="{{ old('custom_days') }}"
                            class="form-control
                                @error('custom_days')
                                is-invalid
                                @enderror"
                            min="1"
                            max="3650"
                            placeholder="Example: 45"
                        >

                        @error('custom_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Reason
                        </label>

                        <textarea
                            name="reason"
                            rows="4"
                            class="form-control
                                @error('reason')
                                is-invalid
                                @enderror"
                            placeholder="Reason for extending the subscription..."
                            required
                        >{{ old('reason') }}</textarea>

                        @error('reason')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="alert alert-info">

                        The extension will be added to the current
                        expiry date.

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm(
                                'Extend this subscription?'
                            )"
                        >
                            Extend Subscription
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

            </div>

        </div>

    </div>

</div>

@endsection