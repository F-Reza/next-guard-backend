@extends('layouts.admin')

@section('title', 'Webhook Details')
@section('page_title', 'Webhook Details')

@section('content')

<div class="mb-4">

    <a
        href="{{ route(
            'admin.payments.webhooks'
        ) }}"
        class="text-decoration-none"
    >
        ← Back to Webhooks
    </a>

    <h4 class="mt-3 mb-1">
        Webhook #{{ $webhook->id }}
    </h4>

    <div class="text-muted">
        {{ $webhook->event_id }}
    </div>

</div>


<div class="row g-4">

    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Webhook Information
            </div>

            <div class="card-body">

                <table class="table align-middle mb-0">

                    <tr>

                        <th style="width: 40%;">
                            Status
                        </th>

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


                                @default

                                    <span class="badge text-bg-secondary">
                                        {{ ucfirst($webhook->status) }}
                                    </span>

                            @endswitch

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Gateway
                        </th>

                        <td>
                            {{ ucfirst($webhook->gateway) }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Event ID
                        </th>

                        <td>
                            {{ $webhook->event_id }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Transaction ID
                        </th>

                        <td>
                            {{ $webhook->transaction_id ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Processed At
                        </th>

                        <td>

                            {{ $webhook->processed_at
                                ? $webhook->processed_at->format(
                                    'd M Y, h:i:s A'
                                )
                                : '—'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Received At
                        </th>

                        <td>

                            {{ $webhook->created_at
                                ? $webhook->created_at->format(
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

        <div class="card h-100">

            <div class="card-header fw-semibold">
                Linked Payment
            </div>

            <div class="card-body">

                @if($payment)

                    <table class="table mb-3">

                        <tr>

                            <th style="width: 40%;">
                                Payment
                            </th>

                            <td>
                                #{{ $payment->id }}
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Transaction
                            </th>

                            <td>
                                {{ $payment->transaction_id }}
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
                                {{ ucfirst($payment->status) }}
                            </td>

                        </tr>

                    </table>


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
                        No matching payment found.
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>


<div class="card mt-4">

    <div class="card-header fw-semibold">
        Webhook Payload
    </div>

    <div class="card-body">

        @if(!empty($webhook->payload))

            <pre
                class="mb-0 p-3 bg-light border rounded"
                style="
                    white-space: pre-wrap;
                    word-break: break-word;
                "
            >{{ json_encode(
                $webhook->payload,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
            ) }}</pre>

        @else

            <span class="text-muted">
                No webhook payload stored.
            </span>

        @endif

    </div>

</div>

@endsection