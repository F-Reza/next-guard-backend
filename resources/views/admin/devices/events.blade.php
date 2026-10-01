@extends('layouts.admin')

@section('title', 'Device Events')
@section('page_title', 'Device Events')

@section('content')

<div class="mb-4">

    <a
        href="{{ route(
            'admin.devices.show',
            $device->id
        ) }}"
        class="text-decoration-none"
    >
        ← Back to Device
    </a>

    <div class="mt-3">

        <h4 class="mb-1">
            Device Events
        </h4>

        <div class="text-muted">
            {{ $device->name ?: 'Unnamed Device' }}
            · Device ID: {{ $device->id }}
        </div>

    </div>

</div>


<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <span class="fw-semibold">
            Event History
        </span>

        <span class="badge text-bg-secondary">
            {{ $events->total() }} Events
        </span>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead>

                <tr>

                    <th style="width: 70%;">
                        Event
                    </th>

                    <th>
                        Time
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($events as $event)

                    <tr>

                        <td>

                            @php
                                $eventName = $event->event;
                            @endphp


                            @if($eventName === 'online')

                                <span class="badge text-bg-success">
                                    Online
                                </span>

                            @elseif($eventName === 'offline')

                                <span class="badge text-bg-secondary">
                                    Offline
                                </span>

                            @elseif(
                                $eventName ===
                                'protection_sync_failed'
                            )

                                <span class="badge text-bg-danger">
                                    Protection Sync Failed
                                </span>

                            @else

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $eventName
                                    )
                                ) }}

                            @endif

                        </td>


                        <td>

                            {{ $event->created_at
                                ? \Illuminate\Support\Carbon::parse(
                                    $event->created_at
                                )->format('d M Y, h:i:s A')
                                : '—'
                            }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="2"
                            class="text-center text-muted py-5"
                        >
                            No device events recorded.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($events->hasPages())

        <div class="card-footer">
            {{ $events->links() }}
        </div>

    @endif

</div>

@endsection