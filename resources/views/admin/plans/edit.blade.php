@extends('layouts.admin')

@section('title', 'Edit Plan')
@section('page_title', 'Edit Plan')

@section('content')

<div class="mb-4">

    <a
        href="{{ route(
            'admin.plans.show',
            $plan->id
        ) }}"
        class="text-decoration-none"
    >
        ← Back to Plan
    </a>

    <h4 class="mt-3 mb-1">
        Edit {{ $plan->name }}
    </h4>

    <div class="text-muted">
        Plan ID: {{ $plan->id }}
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
                Plan Information
            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.plans.update',
                        $plan->id
                    ) }}"
                >

                    @csrf
                    @method('PUT')


                    @include(
                        'admin.plans._form'
                    )


                    <div class="alert alert-warning">

                        Changes to price, duration,
                        features or device limit apply
                        to future subscription operations.

                        Existing subscription records
                        retain their own existing dates
                        and status.

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Save Changes
                        </button>


                        <a
                            href="{{ route(
                                'admin.plans.show',
                                $plan->id
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