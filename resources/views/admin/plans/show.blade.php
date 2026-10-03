@extends('layouts.admin')

@section('title', 'Plan Details')
@section('page_title', 'Plan Details')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4">

    <div>

        <a
            href="{{ route('admin.plans') }}"
            class="text-decoration-none"
        >
            ← Back to Plans
        </a>

        <h4 class="mt-3 mb-1">
            {{ $plan->name }}
        </h4>

        <div class="text-muted">
            Plan ID: {{ $plan->id }}
        </div>

    </div>


    <div class="d-flex gap-2">

        <a
            href="{{ route(
                'admin.plans.edit',
                $plan->id
            ) }}"
            class="btn btn-primary"
        >
            Edit Plan
        </a>


        <form
            method="POST"
            action="{{ route(
                'admin.plans.toggle-status',
                $plan->id
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to change this plan status?'
            )"
        >

            @csrf


            @if($plan->status === 'active')

                <button
                    type="submit"
                    class="btn btn-warning"
                >
                    Deactivate
                </button>

            @else

                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Activate
                </button>

            @endif

        </form>

    </div>

</div>


<div class="row g-4">

    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Plan Information
            </div>

            <div class="card-body">

                <table class="table align-middle mb-0">

                    <tr>

                        <th style="width: 42%;">
                            Status
                        </th>

                        <td>

                            @if($plan->status === 'active')

                                <span class="badge text-bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Price
                        </th>

                        <td>

                            {{ $plan->currency }}

                            {{ number_format(
                                (float) $plan->price,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Duration
                        </th>

                        <td>
                            {{ $plan->duration_days }}
                            days
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Device Limit
                        </th>

                        <td>
                            {{ $plan->device_limit }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Total Subscriptions
                        </th>

                        <td>
                            {{ $plan->subscriptions_count }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Active Subscriptions
                        </th>

                        <td>
                            {{ $activeSubscriptions }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Created At
                        </th>

                        <td>

                            {{ $plan->created_at
                                ? $plan
                                    ->created_at
                                    ->format(
                                        'd M Y, h:i:s A'
                                    )
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Updated At
                        </th>

                        <td>

                            {{ $plan->updated_at
                                ? $plan
                                    ->updated_at
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


    <div class="col-lg-6">

        <div class="card">

            <div class="card-header fw-semibold">
                Description
            </div>

            <div class="card-body">

                @if($plan->description)

                    {{ $plan->description }}

                @else

                    <span class="text-muted">
                        No description configured.
                    </span>

                @endif

            </div>

        </div>


        <div class="card mt-4">

            <div class="card-header fw-semibold">
                Features
            </div>

            <div class="card-body">

                @if(
                    is_array($plan->features)
                    &&
                    count($plan->features)
                )

                    <ul class="mb-0">

                        @foreach(
                            $plan->features
                            as $feature
                        )

                            <li class="mb-2">
                                {{ $feature }}
                            </li>

                        @endforeach

                    </ul>

                @else

                    <span class="text-muted">
                        No features configured.
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>


<div class="card mt-4">

    <div class="card-header fw-semibold">
        Usage Summary
    </div>

    <div class="card-body">

        <div class="row text-center">

            <div class="col-md-4">

                <div class="fs-4 fw-semibold">
                    {{ $plan->subscriptions_count }}
                </div>

                <div class="text-muted">
                    Total Subscriptions
                </div>

            </div>


            <div class="col-md-4">

                <div class="fs-4 fw-semibold">
                    {{ $activeSubscriptions }}
                </div>

                <div class="text-muted">
                    Active Subscriptions
                </div>

            </div>


            <div class="col-md-4">

                <div class="fs-4 fw-semibold">
                    {{ $plan->device_limit }}
                </div>

                <div class="text-muted">
                    Device Limit
                </div>

            </div>

        </div>

    </div>

</div>

@endsection