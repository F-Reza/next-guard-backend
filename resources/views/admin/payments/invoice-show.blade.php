@extends('layouts.admin')

@section('title', 'Invoice Details')
@section('page_title', 'Invoice Details')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('admin.payments.invoices') }}"
        class="text-decoration-none"
    >
        ← Back to Invoices
    </a>

    <h4 class="mt-3 mb-1">
        Invoice #{{ $invoice->id }}
    </h4>

    <div class="text-muted">
        {{ $invoice->invoice_no }}
    </div>

</div>


<div class="row g-4">

    {{-- Invoice Information --}}
    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Invoice Information
            </div>

            <div class="card-body">

                <table class="table align-middle mb-0">

                    <tr>

                        <th style="width: 42%;">
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

        </div>

    </div>


    {{-- User --}}
    <div class="col-lg-6">

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
                            {{ $invoice->user?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Email
                        </th>

                        <td>
                            {{ $invoice->user?->email ?? '—' }}
                        </td>

                    </tr>

                </table>


                @if($invoice->user)

                    <a
                        href="{{ route(
                            'admin.users.show',
                            $invoice->user->id
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

                @if($invoice->subscription)

                    <table class="table mb-3">

                        <tr>

                            <th style="width: 35%;">
                                ID
                            </th>

                            <td>
                                #{{ $invoice->subscription->id }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Plan
                            </th>

                            <td>
                                {{ $invoice->subscription->plan?->name ?? '—' }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Status
                            </th>

                            <td>

                                @switch($invoice->subscription->status)

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


                                    @case('changed')

                                        <span class="badge text-bg-info">
                                            Changed
                                        </span>

                                        @break


                                    @case('expired')

                                        <span class="badge text-bg-secondary">
                                            Expired
                                        </span>

                                        @break


                                    @default

                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst(
                                                $invoice->subscription->status
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
                                {{ $invoice->subscription->device?->name ?? '—' }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Payment Reference
                            </th>

                            <td>
                                {{ $invoice->subscription->payment_reference ?? '—' }}
                            </td>

                        </tr>

                    </table>


                    <a
                        href="{{ route(
                            'admin.subscriptions.show',
                            $invoice->subscription->id
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


{{-- Linked Payment --}}
<div class="card mt-4">

    <div class="card-header fw-semibold">
        Linked Payment
    </div>

    <div class="card-body">

        @if($payment)

            <div class="table-responsive">

                <table class="table align-middle mb-3">

                    <tr>

                        <th style="width: 30%;">
                            Payment ID
                        </th>

                        <td>
                            #{{ $payment->id }}
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
                            Status
                        </th>

                        <td>

                            @if($payment->status === 'paid')

                                <span class="badge text-bg-success">
                                    Paid
                                </span>

                            @elseif($payment->status === 'pending')

                                <span class="badge text-bg-warning">
                                    Pending
                                </span>

                            @elseif($payment->status === 'failed')

                                <span class="badge text-bg-danger">
                                    Failed
                                </span>

                            @elseif($payment->status === 'refunded')

                                <span class="badge text-bg-info">
                                    Refunded
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($payment->status) }}
                                </span>

                            @endif

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

                </table>

            </div>


            <a
                href="{{ route(
                    'admin.payments.show',
                    $payment->id
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >
                View Payment
            </a>

        @else

            <span class="text-muted">
                No payment found for this invoice.
            </span>

        @endif

    </div>

</div>

@endsection