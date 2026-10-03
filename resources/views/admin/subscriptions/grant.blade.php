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


                    {{-- User --}}
                    <div class="mb-3">

                        <label
                            for="user_id"
                            class="form-label"
                        >
                            User
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
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

                                @php

                                    $deviceData = $user->devices
                                        ->map(function ($device) {

                                            return [

                                                'id' =>
                                                    $device->id,

                                                'name' =>
                                                    $device->name
                                                    ?: 'Device #'.$device->id,

                                                'manufacturer' =>
                                                    $device->manufacturer,

                                                'model' =>
                                                    $device->model,

                                                'android_version' =>
                                                    $device->android_version,

                                                'status' =>
                                                    $device->status,

                                            ];

                                        })
                                        ->values()
                                        ->all();

                                @endphp

                                    <option
                                        value="{{ $user->id }}"
                                        data-devices='@json($deviceData)'
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


                    {{-- Device --}}
                    <div class="mb-3">

                        <label
                            for="device_id"
                            class="form-label"
                        >
                            Device
                        </label>

                        <select
                            name="device_id"
                            id="device_id"
                            class="form-select
                                @error('device_id')
                                is-invalid
                                @enderror"
                            required
                            disabled
                        >

                            <option value="">
                                Select User First
                            </option>

                        </select>


                        <div
                            id="deviceHelp"
                            class="form-text"
                        >
                            Select a user to load their active devices.
                        </div>


                        @error('device_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Plan --}}
                    <div class="mb-3">

                        <label
                            for="plan_id"
                            class="form-label"
                        >
                            Plan
                        </label>

                        <select
                            name="plan_id"
                            id="plan_id"
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
                                    data-device-limit="{{ $plan->device_limit }}"
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


                    {{-- Grant Reason --}}
                    <div class="mb-3">

                        <label
                            for="reason"
                            class="form-label"
                        >
                            Grant Reason
                        </label>

                        <textarea
                            name="reason"
                            id="reason"
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

                        The selected device will be linked
                        to this subscription.

                        Protection activation will run
                        immediately for the selected device.

                        Source will be recorded as

                        <strong>
                            Admin
                        </strong>.

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            id="grantButton"
                            class="btn btn-primary"
                            disabled
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


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const userSelect =
            document.getElementById('user_id');

        const deviceSelect =
            document.getElementById('device_id');

        const planSelect =
            document.getElementById('plan_id');

        const deviceHelp =
            document.getElementById('deviceHelp');

        const grantButton =
            document.getElementById('grantButton');

        const oldDeviceId =
            @json(old('device_id'));


        /*
        |--------------------------------------------------------------------------
        | Enable / Disable Grant Button
        |--------------------------------------------------------------------------
        */

        function updateGrantButton() {

            const hasUser =
                userSelect.value !== '';

            const hasDevice =
                deviceSelect.value !== '';

            const hasPlan =
                planSelect.value !== '';

            grantButton.disabled = !(
                hasUser
                &&
                hasDevice
                &&
                hasPlan
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Load Devices for Selected User
        |--------------------------------------------------------------------------
        */

        function loadDevices() {

            const selectedOption =
                userSelect.options[
                    userSelect.selectedIndex
                ];


            deviceSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | No User Selected
            |--------------------------------------------------------------------------
            */

            if (
                !selectedOption
                ||
                !selectedOption.value
            ) {

                deviceSelect.disabled = true;

                deviceSelect.innerHTML =
                    '<option value="">'
                    + 'Select User First'
                    + '</option>';

                deviceHelp.textContent =
                    'Select a user to load their active devices.';

                updateGrantButton();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Parse Device Data
            |--------------------------------------------------------------------------
            */

            let devices = [];

            try {

                devices = JSON.parse(
                    selectedOption.dataset.devices
                    || '[]'
                );

            } catch (error) {

                console.error(
                    'Unable to parse device data.',
                    error
                );

                devices = [];

            }


            /*
            |--------------------------------------------------------------------------
            | User Has No Active Device
            |--------------------------------------------------------------------------
            */

            if (devices.length === 0) {

                deviceSelect.disabled = true;

                deviceSelect.innerHTML =
                    '<option value="">'
                    + 'No Active Devices'
                    + '</option>';

                deviceHelp.textContent =
                    'This user has no active devices. '
                    + 'A subscription cannot be granted.';

                updateGrantButton();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Populate Device Dropdown
            |--------------------------------------------------------------------------
            */

            deviceSelect.disabled = false;

            deviceSelect.innerHTML =
                '<option value="">'
                + 'Select Device'
                + '</option>';


            devices.forEach(function (device) {

                const option =
                    document.createElement('option');

                option.value =
                    device.id;


                let label =
                    device.name;


                const deviceDetails = [
                    device.manufacturer,
                    device.model
                ]
                .filter(Boolean)
                .join(' ');


                if (deviceDetails) {

                    label +=
                        ' — '
                        + deviceDetails;

                }


                if (device.android_version) {

                    label +=
                        ' — Android '
                        + device.android_version;

                }

                if (device.status) {

                    label +=
                        ' — '
                        + device.status.toUpperCase();

                }


                option.textContent =
                    label;


                /*
                |--------------------------------------------------------------------------
                | Restore Previous Selection After Validation Error
                |--------------------------------------------------------------------------
                */

                if (
                    oldDeviceId
                    &&
                    String(oldDeviceId)
                    ===
                    String(device.id)
                ) {

                    option.selected =
                        true;

                }


                deviceSelect.appendChild(
                    option
                );

            });


            deviceHelp.textContent =
                devices.length
                + ' active device(s) available.';


            updateGrantButton();

        }


        /*
        |--------------------------------------------------------------------------
        | Event Listeners
        |--------------------------------------------------------------------------
        */

        userSelect.addEventListener(
            'change',
            function () {

                loadDevices();

            }
        );


        deviceSelect.addEventListener(
            'change',
            function () {

                updateGrantButton();

            }
        );


        planSelect.addEventListener(
            'change',
            function () {

                updateGrantButton();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial State
        |--------------------------------------------------------------------------
        */

        loadDevices();

        updateGrantButton();

    }
);

</script>

@endsection