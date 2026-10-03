@extends('layouts.admin')

@section('title', 'Create Plan')
@section('page_title', 'Create Plan')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('admin.plans') }}"
        class="text-decoration-none"
    >
        ← Back to Plans
    </a>

    <h4 class="mt-3 mb-1">
        Create Subscription Plan
    </h4>

    <div class="text-muted">
        Create a new subscription plan.
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
                        'admin.plans.store'
                    ) }}"
                >

                    @csrf


                    @include(
                        'admin.plans._form'
                    )


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Create Plan
                        </button>


                        <a
                            href="{{ route(
                                'admin.plans'
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