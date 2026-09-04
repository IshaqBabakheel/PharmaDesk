@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1">Activity Details</h4>
            <p class="text-muted mb-0">
                Audit record #{{ $activity->id }}
            </p>
        </div>

        <a
            href="{{ route('audit-logs.index') }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Activity Logs
        </a>
    </div>

    <div class="row g-4">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong>Activity Information</strong>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">
                                Date & Time
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->created_at?->format('d M Y, h:i:s A') ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">
                                User
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->causer?->name ?? 'System' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">
                                Action
                            </div>

                            @php
                                $event = strtolower($activity->event ?? 'activity');

                                $badge = match ($event) {
                                    'created',
                                    'completed',
                                    'login' => 'bg-success',

                                    'updated' => 'bg-primary',

                                    'deleted',
                                    'cancelled',
                                    'login_failed' => 'bg-danger',

                                    'payment',
                                    'payment_recorded',
                                    'recorded' => 'bg-info text-dark',

                                    default => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $event)) }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">
                                Log Name
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->log_name ?? 'default' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">
                                Module
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->module_name }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">
                                Record
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->record_label }}
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div class="fw-semibold">
                                {{ $activity->description }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong>Subject Information</strong>
                </div>

                <div class="card-body">

                    @if($activity->subject)

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="text-muted small mb-1">
                                    Model
                                </div>

                                <div class="fw-semibold">
                                    {{ class_basename($activity->subject_type) }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small mb-1">
                                    Record ID
                                </div>

                                <div class="fw-semibold">
                                    {{ $activity->subject_id }}
                                </div>
                            </div>

                        </div>

                    @else

                        <div class="text-muted">
                            This activity is not attached to a database record.
                        </div>

                    @endif

                </div>
            </div>

            @php
                $changes = $activity->attribute_changes ?? collect();
            @endphp

            @if($changes->isNotEmpty())

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <strong>Attribute Changes</strong>
                    </div>

                    <div class="card-body">
                        @include('audit_logs.partials.changes', [
                            'activity' => $activity
                        ])
                    </div>
                </div>

            @endif

        </div>

        <div class="col-xl-4">

            @php
                $properties = $activity->properties ?? collect();
            @endphp

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong>Request Information</strong>
                </div>

                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-4">
                            IP Address
                        </dt>

                        <dd class="col-sm-8 text-break">
                            {{ $properties->get('ip', '—') }}
                        </dd>

                        <dt class="col-sm-4">
                            URL
                        </dt>

                        <dd class="col-sm-8 text-break">
                            {{ $properties->get('url', '—') }}
                        </dd>

                        <dt class="col-sm-4">
                            User Agent
                        </dt>

                        <dd class="col-sm-8 text-break small">
                            {{ $properties->get('user_agent', '—') }}
                        </dd>

                    </dl>

                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <strong>Custom Properties</strong>
                </div>

                <div class="card-body">

                    @if($properties->isNotEmpty())

                        <pre class="bg-light rounded p-3 small mb-0">{{ json_encode($properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>

                    @else

                        <div class="text-muted">
                            No custom properties.
                        </div>

                    @endif

                </div>
            </div>

        </div>

    </div>

</div>
@endsection