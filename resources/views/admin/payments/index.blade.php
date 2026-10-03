@extends('layouts.admin')

@section('title', 'Payments')
@section('page_title', 'Payments')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="mb-1">
            Payment Management
        </h4>

        <div class="text-muted">
            View and inspect payment transactions.
        </div>

    </div>


    <div class="d-flex gap-2">

        <a
            href="{{ route(
                'admin.payments.invoices'
            ) }}"
            class="btn btn-outline-primary"
        >
            Invoices
        </a>

        <a
            href="{{ route(
                'admin.payments.webhooks'
            ) }}"
            class="btn btn-outline-secondary"
        >
            Webhook History
        </a>

    </div>

</div>


<div class="card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.payments') }}"
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
                    placeholder="User, email, transaction ID..."
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

                    @foreach([
                        'pending',
                        'paid',
                        'failed',
                        'refunded'
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

        <table
            class="table
                table-hover
                align-middle
                mb-0"
        >

            <thead>

                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Transaction</th>
                    <th>Gateway</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th></th>
                </tr>

            </thead>


            <tbody>

            @forelse($payments as $payment)

                <tr>

                    <td>
                        #{{ $payment->id }}
                    </td>


                    <td>

                        <div class="fw-semibold">
                            {{ $payment->user?->name ?? '—' }}
                        </div>

                        <small class="text-muted">
                            {{ $payment->user?->email ?? '—' }}
                        </small>

                    </td>


                    <td>

                        <div>
                            {{ $payment->transaction_id }}
                        </div>

                        @if($payment->provider_transaction_id)

                            <small class="text-muted">
                                Provider:
                                {{ $payment->provider_transaction_id }}
                            </small>

                        @endif

                    </td>


                    <td>

                        {{ $payment->gateway
                            ? ucfirst($payment->gateway)
                            : '—'
                        }}

                    </td>


                    <td>

                        {{ $payment->currency }}

                        {{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}

                    </td>


                    <td>

                        @switch($payment->status)

                            @case('paid')

                                <span class="badge text-bg-success">
                                    Paid
                                </span>

                                @break


                            @case('pending')

                                <span class="badge text-bg-warning">
                                    Pending
                                </span>

                                @break


                            @case('failed')

                                <span class="badge text-bg-danger">
                                    Failed
                                </span>

                                @break


                            @case('refunded')

                                <span class="badge text-bg-info">
                                    Refunded
                                </span>

                                @break


                            @default

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($payment->status) }}
                                </span>

                        @endswitch

                    </td>


                    <td>

                        {{ $payment->paid_at
                            ? $payment->paid_at->format(
                                'd M Y, h:i:s A'
                            )
                            : '—'
                        }}

                    </td>


                    <td class="text-end">

                        <a
                            href="{{ route(
                                'admin.payments.show',
                                $payment->id
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
                        No payments found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($payments->hasPages())

        <div class="card-footer">
            {{ $payments->links() }}
        </div>

    @endif

</div>

@endsection