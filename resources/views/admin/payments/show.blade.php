@extends('layouts.admin')

@section('title', 'Payment Details')
@section('page_title', 'Payment Details')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('admin.payments') }}"
        class="text-decoration-none"
    >
        ← Back to Payments
    </a>

    <h4 class="mt-3 mb-1">
        Payment #{{ $payment->id }}
    </h4>

    <div class="text-muted">
        {{ $payment->transaction_id }}
    </div>

</div>


<div class="row g-4">

    {{-- Payment Information --}}
    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Payment Information
            </div>

            <div class="card-body">

                <table class="table align-middle mb-0">

                    <tr>

                        <th style="width: 42%;">
                            Status
                        </th>

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

                    </tr>


                    <tr>

                        <th>
                            Amount
                        </th>

                        <td>

                            {{ $payment->currency }}

                            {{ number_format(
                                (float) $payment->amount,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Gateway
                        </th>

                        <td>

                            {{ $payment->gateway
                                ? ucfirst($payment->gateway)
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Provider
                        </th>

                        <td>
                            {{ $payment->provider ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Transaction ID
                        </th>

                        <td>
                            {{ $payment->transaction_id }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Provider Transaction ID
                        </th>

                        <td>
                            {{ $payment->provider_transaction_id ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Paid At
                        </th>

                        <td>

                            {{ $payment->paid_at
                                ? $payment->paid_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Verified At
                        </th>

                        <td>

                            {{ $payment->verified_at
                                ? $payment->verified_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Created At
                        </th>

                        <td>

                            {{ $payment->created_at
                                ? $payment->created_at->format(
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

                            {{ $payment->updated_at
                                ? $payment->updated_at->format(
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


    {{-- User + Subscription --}}
    <div class="col-lg-6">

        {{-- User --}}
        <div class="card">

            <div class="card-header fw-semibold">
                User
            </div>

            <div class="card-body">

                <table class="table mb-3">

                    <tr>

                        <th style="width: 35%;">
                            Name
                        </th>

                        <td>
                            {{ $payment->user?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Email
                        </th>

                        <td>
                            {{ $payment->user?->email ?? '—' }}
                        </td>

                    </tr>

                </table>


                @if($payment->user)

                    <a
                        href="{{ route(
                            'admin.users.show',
                            $payment->user->id
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View User
                    </a>

                @endif

            </div>

        </div>


        {{-- Subscription --}}
        <div class="card mt-4">

            <div class="card-header fw-semibold">
                Subscription
            </div>

            <div class="card-body">

                @if($payment->subscription)

                    <table class="table mb-3">

                        <tr>

                            <th style="width: 35%;">
                                ID
                            </th>

                            <td>
                                #{{ $payment->subscription->id }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Plan
                            </th>

                            <td>
                                {{ $payment->subscription->plan?->name ?? '—' }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Status
                            </th>

                            <td>

                                @switch($payment->subscription->status)

                                    @case('active')

                                        <span class="badge text-bg-success">
                                            Active
                                        </span>

                                        @break


                                    @case('cancelled')

                                        <span class="badge text-bg-warning">
                                            Cancelled
                                        </span>

                                        @break


                                    @case('expired')

                                        <span class="badge text-bg-secondary">
                                            Expired
                                        </span>

                                        @break


                                    @case('changed')

                                        <span class="badge text-bg-info">
                                            Changed
                                        </span>

                                        @break


                                    @default

                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst(
                                                $payment->subscription->status
                                            ) }}
                                        </span>

                                @endswitch

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Device
                            </th>

                            <td>
                                {{ $payment->subscription->device?->name ?? '—' }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Payment Reference
                            </th>

                            <td>
                                {{ $payment->subscription->payment_reference ?? '—' }}
                            </td>

                        </tr>

                    </table>


                    <a
                        href="{{ route(
                            'admin.subscriptions.show',
                            $payment->subscription->id
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View Subscription
                    </a>

                @else

                    <span class="text-muted">
                        No subscription linked.
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- Invoice --}}
<div class="card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <span class="fw-semibold">
            Invoice
        </span>

        @if($invoice)

            @if($invoice->status === 'paid')

                <span class="badge text-bg-success">
                    Paid
                </span>

            @elseif($invoice->status === 'unpaid')

                <span class="badge text-bg-warning">
                    Unpaid
                </span>

            @elseif($invoice->status === 'cancelled')

                <span class="badge text-bg-secondary">
                    Cancelled
                </span>

            @endif

        @endif

    </div>


    <div class="card-body">

        @if($invoice)

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <tr>

                        <th style="width: 30%;">
                            Invoice Number
                        </th>

                        <td>
                            {{ $invoice->invoice_no }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Amount
                        </th>

                        <td>

                            {{ $invoice->currency }}

                            {{ number_format(
                                (float) $invoice->amount,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Status
                        </th>

                        <td>

                            @if($invoice->status === 'paid')

                                <span class="badge text-bg-success">
                                    Paid
                                </span>

                            @elseif($invoice->status === 'unpaid')

                                <span class="badge text-bg-warning">
                                    Unpaid
                                </span>

                            @elseif($invoice->status === 'cancelled')

                                <span class="badge text-bg-secondary">
                                    Cancelled
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($invoice->status) }}
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Created At
                        </th>

                        <td>

                            {{ $invoice->created_at
                                ? $invoice->created_at->format(
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

                            {{ $invoice->updated_at
                                ? $invoice->updated_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>

                    </tr>

                </table>

            </div>

        @else

            <span class="text-muted">
                No invoice found for this payment.
            </span>

        @endif

    </div>

</div>


{{-- Related Webhooks --}}
<div class="card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <span class="fw-semibold">
            Related Webhooks
        </span>

        <span class="badge text-bg-secondary">
            {{ $webhooks->count() }}
        </span>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead>

                <tr>
                    <th>Event ID</th>
                    <th>Transaction</th>
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
                        {{ $webhook->event_id }}
                    </td>


                    <td>
                        {{ $webhook->transaction_id ?? '—' }}
                    </td>


                    <td>
                        {{ ucfirst($webhook->gateway) }}
                    </td>


                    <td>

                        @if($webhook->status === 'processed')

                            <span class="badge text-bg-success">
                                Processed
                            </span>

                        @elseif($webhook->status === 'failed')

                            <span class="badge text-bg-danger">
                                Failed
                            </span>

                        @elseif($webhook->status === 'processing')

                            <span class="badge text-bg-warning">
                                Processing
                            </span>

                        @elseif($webhook->status === 'pending')

                            <span class="badge text-bg-secondary">
                                Pending
                            </span>

                        @else

                            <span class="badge text-bg-secondary">
                                {{ ucfirst($webhook->status) }}
                            </span>

                        @endif

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
                        colspan="6"
                        class="text-center text-muted py-4"
                    >
                        No webhook events associated with this transaction.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- Gateway Response --}}
<div class="card mt-4">

    <div class="card-header fw-semibold">
        Gateway Response
    </div>

    <div class="card-body">

        @if(!empty($payment->gateway_response))

            <pre
                class="mb-0 p-3 bg-light border rounded"
                style="
                    white-space: pre-wrap;
                    word-break: break-word;
                "
            >{{ json_encode(
                $payment->gateway_response,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
            ) }}</pre>

        @else

            <span class="text-muted">
                No gateway response stored.
            </span>

        @endif

    </div>

</div>

@endsection