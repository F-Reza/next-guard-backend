@extends('layouts.admin')

@section('title', 'Invoices')
@section('page_title', 'Invoices')

@section('content')

<div class="mb-4">

    <a
        href="{{ route('admin.payments') }}"
        class="text-decoration-none"
    >
        ← Back to Payments
    </a>

    <h4 class="mt-3 mb-1">
        Invoice Management
    </h4>

    <div class="text-muted">
        View subscription invoices.
    </div>

</div>


<div class="card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route(
                'admin.payments.invoices'
            ) }}"
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
                    placeholder="Invoice number, user or email..."
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
                        'paid',
                        'unpaid',
                        'cancelled'
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
                    <th>Invoice</th>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th></th>
                </tr>

            </thead>


            <tbody>

            @forelse($invoices as $invoice)

                <tr>

                    <td>
                        #{{ $invoice->id }}
                    </td>


                    <td>
                        {{ $invoice->invoice_no }}
                    </td>


                    <td>

                        <div class="fw-semibold">
                            {{ $invoice->user?->name ?? '—' }}
                        </div>

                        <small class="text-muted">
                            {{ $invoice->user?->email ?? '—' }}
                        </small>

                    </td>


                    <td>
                        {{ $invoice->subscription?->plan?->name ?? '—' }}
                    </td>


                    <td>

                        {{ $invoice->currency }}

                        {{ number_format(
                            (float) $invoice->amount,
                            2
                        ) }}

                    </td>


                    <td>

                        @if($invoice->status === 'paid')

                            <span class="badge text-bg-success">
                                Paid
                            </span>

                        @elseif($invoice->status === 'unpaid')

                            <span class="badge text-bg-warning">
                                Unpaid
                            </span>

                        @else

                            <span class="badge text-bg-secondary">
                                {{ ucfirst($invoice->status) }}
                            </span>

                        @endif

                    </td>


                    <td>

                        {{ $invoice->created_at
                            ? $invoice->created_at->format(
                                'd M Y, h:i:s A'
                            )
                            : '—'
                        }}

                    </td>


                    <td class="text-end">

                        <a
                            href="{{ route(
                                'admin.payments.invoices.show',
                                $invoice->id
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
                        No invoices found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($invoices->hasPages())

        <div class="card-footer">
            {{ $invoices->links() }}
        </div>

    @endif

</div>

@endsection