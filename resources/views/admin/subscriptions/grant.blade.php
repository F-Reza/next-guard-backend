@extends('layouts.admin')

@section('title', 'Grant Subscription')
@section('page_title', 'Grant Subscription')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('admin.subscriptions') }}"
        class="text-decoration-none"
    >
        ← Back to Subscriptions
    </a>

    <h4 class="mt-3 mb-1">
        Grant Subscription
    </h4>

    <div class="text-muted">
        Create a new subscription manually for a user.
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


<div class="row">

    <div class="col-lg-8">

        <div class="card">

            <div class="card-header fw-semibold">
                Subscription Details
            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.subscriptions.grant.store'
                    ) }}"
                >

                    @csrf


                    <div class="mb-3">

                        <label class="form-label">
                            User
                        </label>

                        <select
                            name="user_id"
                            class="form-select
                                @error('user_id')
                                is-invalid
                                @enderror"
                            required
                        >

                            <option value="">
                                Select User
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old('user_id')
                                        ==
                                        $user->id
                                    )
                                >

                                    {{ $user->name }}

                                    @if($user->email)
                                        — {{ $user->email }}
                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @error('user_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Plan
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
                                Select Plan
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


                    <div class="mb-3">

                        <label class="form-label">
                            Device ID
                            <span class="text-muted">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="number"
                            name="device_id"
                            value="{{ old('device_id') }}"
                            class="form-control
                                @error('device_id')
                                is-invalid
                                @enderror"
                            min="1"
                            placeholder="Example: 3"
                        >


                        <div class="form-text">

                            Leave empty if the subscription
                            is not being tied to a specific
                            device yet.

                        </div>


                        @error('device_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Grant Reason
                        </label>

                        <textarea
                            name="reason"
                            rows="4"
                            class="form-control
                                @error('reason')
                                is-invalid
                                @enderror"
                            required
                            placeholder="Why is this subscription being granted?"
                        >{{ old('reason') }}</textarea>


                        @error('reason')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="alert alert-info">

                        The subscription will start immediately.

                        Its expiry date will be calculated
                        automatically from the selected
                        plan duration.

                        Source will be recorded as

                        <strong>
                            Admin
                        </strong>.

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm(
                                'Grant this subscription?'
                            )"
                        >
                            Grant Subscription
                        </button>


                        <a
                            href="{{ route(
                                'admin.subscriptions'
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