@extends('layouts.admin')

@section('title', 'Payment Webhooks')
@section('page_title', 'Payment Webhooks')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('admin.payments') }}"
        class="text-decoration-none"
    >
        ← Back to Payments
    </a>

    <h4 class="mt-3 mb-1">
        Payment Webhook History
    </h4>

    <div class="text-muted">
        View gateway webhook processing events.
    </div>

</div>


<div class="card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route(
                'admin.payments.webhooks'
            ) }}"
            class="row g-3"
        >

            <div class="col-lg-5">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Event ID or transaction ID..."
                >

            </div>


            <div class="col-lg-3">

                <label class="form-label">
                    Gateway
                </label>

                <select
                    name="gateway"
                    class="form-select"
                >

                    <option value="">
                        All Gateways
                    </option>

                    @foreach($gateways as $gateway)

                        <option
                            value="{{ $gateway }}"
                            @selected(
                                request('gateway')
                                === $gateway
                            )
                        >
                            {{ ucfirst($gateway) }}
                        </option>

                    @endforeach

                </select>

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

                    @foreach([
                        'pending',
                        'processing',
                        'processed',
                        'failed'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(
                                request('status')
                                === $status
                            )
                        >
                            {{ ucfirst($status) }}
                        </option>

                    @endforeach

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
                    <th>Event ID</th>
                    <th>Transaction ID</th>
                    <th>Gateway</th>
                    <th>Status</th>
                    <th>Processed At</th>
                    <th></th>
                </tr>

            </thead>


            <tbody>

            @forelse($webhooks as $webhook)

                <tr>

                    <td>
                        #{{ $webhook->id }}
                    </td>


                    <td>
                        {{ $webhook->event_id }}
                    </td>


                    <td>
                        {{ $webhook->transaction_id ?? '—' }}
                    </td>


                    <td>
                        {{ ucfirst($webhook->gateway) }}
                    </td>


                    <td>

                        @switch($webhook->status)

                            @case('processed')

                                <span class="badge text-bg-success">
                                    Processed
                                </span>

                                @break


                            @case('failed')

                                <span class="badge text-bg-danger">
                                    Failed
                                </span>

                                @break


                            @case('processing')

                                <span class="badge text-bg-warning">
                                    Processing
                                </span>

                                @break


                            @case('pending')

                                <span class="badge text-bg-secondary">
                                    Pending
                                </span>

                                @break


                            @default

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($webhook->status) }}
                                </span>

                        @endswitch

                    </td>


                    <td>

                        {{ $webhook->processed_at
                            ? $webhook->processed_at->format(
                                'd M Y, h:i:s A'
                            )
                            : '—'
                        }}

                    </td>


                    <td class="text-end">

                        <a
                            href="{{ route(
                                'admin.payments.webhooks.show',
                                $webhook->id
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
                        colspan="7"
                        class="text-center text-muted py-5"
                    >
                        No payment webhooks found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($webhooks->hasPages())

        <div class="card-footer">
            {{ $webhooks->links() }}
        </div>

    @endif

</div>

@endsection