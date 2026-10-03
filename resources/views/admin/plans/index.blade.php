@extends('layouts.admin')

@section('title', 'Plans')
@section('page_title', 'Plans')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="mb-1">
            Subscription Plans
        </h4>

        <div class="text-muted">
            Manage pricing, duration, features and device limits.
        </div>

    </div>


    <a
        href="{{ route('admin.plans.create') }}"
        class="btn btn-primary"
    >
        Create Plan
    </a>

</div>


<div class="card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.plans') }}"
            class="row g-3"
        >

            <div class="col-lg-8">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Plan name or description..."
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
                        value="inactive"
                        @selected(
                            request('status') === 'inactive'
                        )
                    >
                        Inactive
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
                    <th>Plan</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Device Limit</th>
                    <th>Subscriptions</th>
                    <th>Status</th>
                    <th></th>
                </tr>

            </thead>


            <tbody>

            @forelse($plans as $plan)

                <tr>

                    <td>
                        #{{ $plan->id }}
                    </td>


                    <td>

                        <div class="fw-semibold">
                            {{ $plan->name }}
                        </div>

                        <small class="text-muted">

                            {{ \Illuminate\Support\Str::limit(
                                $plan->description,
                                55
                            ) }}

                        </small>

                    </td>


                    <td>

                        {{ $plan->currency }}

                        {{ number_format(
                            (float) $plan->price,
                            2
                        ) }}

                    </td>


                    <td>
                        {{ $plan->duration_days }} days
                    </td>


                    <td>
                        {{ $plan->device_limit }}
                    </td>


                    <td>
                        {{ $plan->subscriptions_count }}
                    </td>


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


                    <td class="text-end">

                        <a
                            href="{{ route(
                                'admin.plans.show',
                                $plan->id
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
                        colspan="8"
                        class="text-center text-muted py-5"
                    >
                        No subscription plans found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($plans->hasPages())

        <div class="card-footer">
            {{ $plans->links() }}
        </div>

    @endif

</div>

@endsection