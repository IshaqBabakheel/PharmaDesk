@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1">Notifications</h4>
            <p class="text-muted mb-0">
                System alerts and important reminders.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-outline-primary"
            id="markAllRead"
        >
            <i class="fa-solid fa-check-double me-1"></i>
            Mark All as Read
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            @forelse($notifications as $notification)

                @php
                    $data = $notification->data ?? [];
                    $type = $data['type'] ?? 'system';

                    $icon = match ($type) {
                        'low_stock' => 'fa-solid fa-box-open text-warning',
                        'expiry' => 'fa-solid fa-calendar-xmark text-warning',
                        'expired' => 'fa-solid fa-triangle-exclamation text-danger',
                        'customer_due' => 'fa-solid fa-user-clock text-primary',
                        'supplier_due' => 'fa-solid fa-truck-clock text-primary',
                        default => 'fa-solid fa-bell text-secondary',
                    };
                @endphp

                <div
                    class="notification-item border-bottom p-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                    data-id="{{ $notification->id }}"
                >
                    <div class="d-flex gap-3">

                        <div class="fs-4" style="width: 35px;">
                            <i class="{{ $icon }}"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between gap-3">
                                <strong>
                                    {{ $data['title'] ?? 'Notification' }}
                                </strong>

                                <small class="text-muted text-nowrap">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </small>
                            </div>

                            <div class="text-muted mt-1">
                                {{ $data['message'] ?? '' }}
                            </div>

                            @if(!empty($data['batch_number']))
                                <div class="small mt-2">
                                    <strong>Batch:</strong>
                                    {{ $data['batch_number'] }}
                                </div>
                            @endif

                            @if(isset($data['amount']))
                                <div class="small mt-2">
                                    <strong>Amount:</strong>
                                    Rs. {{ number_format((float) $data['amount'], 2) }}
                                </div>
                            @endif

                            <div class="mt-2 d-flex gap-2">

                                @if(!empty($data['url']))
                                    <a
                                        href="{{ $data['url'] }}"
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="markNotificationRead('{{ $notification->id }}')"
                                    >
                                        View
                                    </a>
                                @endif

                                @unless($notification->read_at)
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light border mark-read"
                                        data-id="{{ $notification->id }}"
                                    >
                                        Mark as Read
                                    </button>
                                @endunless

                            </div>
                        </div>

                    </div>
                </div>

            @empty

                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-bell-slash fs-2 d-block mb-3"></i>
                    No notifications found.
                </div>

            @endforelse

        </div>

        @if($notifications->hasPages())
            <div class="card-footer bg-white">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
$(function () {

    $('.mark-read').on('click', function () {
        markNotificationRead($(this).data('id'), true);
    });

    $('#markAllRead').on('click', function () {
        $.ajax({
            url: @json(route('notifications.read-all')),
            type: 'PATCH',
            data: {
                _token: @json(csrf_token())
            },
            success: function () {
                window.location.reload();
            }
        });
    });

});

function markNotificationRead(id, reload = false) {
    $.ajax({
        url: @json(url('/notifications')) + '/' + id + '/read',
        type: 'PATCH',
        data: {
            _token: @json(csrf_token())
        },
        success: function () {
            if (reload) {
                window.location.reload();
            }
        }
    });
}
</script>
@endpush