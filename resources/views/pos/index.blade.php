@extends('layouts.pos')
@push('styles')
    <style>
/* ============================================================
   PREMIUM PHARMACY / MEDICAL LOADER
   ============================================================ */

.loading-grid-overlay {
    grid-column: 1 / -1;
    grid-row: 1 / -1;

    min-height: 430px;
    padding: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    position: relative;
    overflow: hidden;

    background:
        radial-gradient(circle at 50% 40%,
            rgba(37, 99, 235, 0.08) 0%,
            rgba(37, 99, 235, 0.025) 30%,
            transparent 65%),
        linear-gradient(135deg, #f8fbff 0%, #eef4fb 100%);

    border: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 18px;
}


/* ============================================================
   BACKGROUND DECORATION
   ============================================================ */

.loading-grid-overlay::before {
    content: "";
    position: absolute;

    width: 420px;
    height: 420px;

    top: 50%;
    left: 50%;

    transform: translate(-50%, -50%);

    border-radius: 50%;

    border: 1px solid rgba(37, 99, 235, 0.07);

    box-shadow:
        0 0 0 45px rgba(37, 99, 235, 0.018),
        0 0 0 90px rgba(37, 99, 235, 0.012);

    animation: pharmacy-orbit 8s linear infinite;
}


.loading-grid-overlay::after {
    content: "";

    position: absolute;

    width: 180px;
    height: 180px;

    top: 20%;
    right: 15%;

    border-radius: 50%;

    background: rgba(96, 165, 250, 0.06);

    filter: blur(40px);

    animation: pharmacy-glow 5s ease-in-out infinite;
}


@keyframes pharmacy-orbit {
    from {
        transform: translate(-50%, -50%) rotate(0deg);
    }

    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}


@keyframes pharmacy-glow {

    0%,
    100% {
        opacity: .4;
        transform: scale(1);
    }

    50% {
        opacity: .8;
        transform: scale(1.25);
    }
}


/* ============================================================
   CONTENT
   ============================================================ */

.loading-grid-content {
    position: relative;
    z-index: 5;

    text-align: center;

    padding: 25px 30px;

    max-width: 430px;
}


/* ============================================================
   PREMIUM MEDICAL LOADER
   ============================================================ */

.pharmacy-loader {
    width: 120px;
    height: 120px;

    margin: 0 auto 28px;

    position: relative;

    display: flex;
    align-items: center;
    justify-content: center;
}


/* Outer rotating ring */

.pharmacy-loader-ring {
    position: absolute;

    width: 112px;
    height: 112px;

    border-radius: 50%;

    border: 2px solid transparent;

    border-top-color: #2563eb;
    border-right-color: #60a5fa;

    animation: pharmacy-ring-spin 2.2s linear infinite;
}


.pharmacy-loader-ring::before {
    content: "";

    position: absolute;

    width: 7px;
    height: 7px;

    top: 9px;
    left: 17px;

    border-radius: 50%;

    background: #2563eb;

    box-shadow:
        0 0 10px rgba(37, 99, 235, .7),
        0 0 20px rgba(37, 99, 235, .35);
}


/* Inner ring */

.pharmacy-loader-ring-inner {
    position: absolute;

    width: 88px;
    height: 88px;

    border-radius: 50%;

    border: 1px dashed rgba(37, 99, 235, .25);

    animation: pharmacy-inner-spin 6s linear infinite reverse;
}


@keyframes pharmacy-ring-spin {
    to {
        transform: rotate(360deg);
    }
}


@keyframes pharmacy-inner-spin {
    to {
        transform: rotate(360deg);
    }
}


/* ============================================================
   CAPSULE
   ============================================================ */

.pharmacy-capsule {
    position: relative;

    width: 58px;
    height: 30px;

    border-radius: 18px;

    transform: rotate(-45deg);

    background: linear-gradient(
        90deg,
        #2563eb 0%,
        #3b82f6 48%,
        #ffffff 49%,
        #f8fafc 100%
    );

    box-shadow:
        0 8px 20px rgba(37, 99, 235, .25),
        0 0 25px rgba(37, 99, 235, .12);

    animation: capsule-float 2.4s ease-in-out infinite;
}


/* Capsule highlight */

.pharmacy-capsule::before {
    content: "";

    position: absolute;

    top: 4px;
    left: 7px;

    width: 19px;
    height: 5px;

    border-radius: 10px;

    background: rgba(255, 255, 255, .45);

    filter: blur(1px);
}


/* Capsule divider */

.pharmacy-capsule::after {
    content: "";

    position: absolute;

    top: 0;
    left: 50%;

    width: 2px;
    height: 100%;

    background: rgba(148, 163, 184, .2);
}


@keyframes capsule-float {

    0%,
    100% {
        transform: rotate(-45deg) translateY(0);
    }

    50% {
        transform: rotate(-45deg) translateY(-7px);
    }
}


/* ============================================================
   MEDICAL CROSS CENTER
   ============================================================ */

.pharmacy-cross {
    position: absolute;

    width: 25px;
    height: 25px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 18px;
    font-weight: 800;

    color: #2563eb;

    background: rgba(255, 255, 255, .94);

    border-radius: 50%;

    box-shadow:
        0 3px 10px rgba(15, 23, 42, .12);

    animation: cross-heartbeat 1.6s ease-in-out infinite;
}


@keyframes cross-heartbeat {

    0%,
    100% {
        transform: scale(1);
    }

    15% {
        transform: scale(1.15);
    }

    30% {
        transform: scale(1);
    }

    45% {
        transform: scale(1.08);
    }

    60% {
        transform: scale(1);
    }
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 768px) {

    .loading-grid-overlay {
        min-height: 330px;
        padding: 25px;
    }

    .pharmacy-loader {
        width: 95px;
        height: 95px;

        margin-bottom: 20px;
    }

    .pharmacy-loader-ring {
        width: 90px;
        height: 90px;
    }

    .pharmacy-loader-ring-inner {
        width: 70px;
        height: 70px;
    }

}

/* ============================================================
   EMPTY STATE
   ============================================================ */

.loading-grid-overlay.empty {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
}

.loading-grid-overlay.empty .empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

/* ============================================================
   TITLE & SUBTITLE (if missing)
   ============================================================ */

.loading-grid-title {
    font-size: 22px;
    font-weight: 700;
    color: #1E293B;
    margin-bottom: 6px;
}

.loading-grid-subtitle {
    font-size: 14px;
    color: #64748B;
    margin-bottom: 4px;
}

.loading-grid-title.text-danger {
    color: #DC2626 !important;
}

/* ============================================================
   ERROR ICON
   ============================================================ */

.loading-grid-overlay.error .error-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.loading-grid-overlay.error {
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
}
</style>



@endpush
@section('title', 'Point of Sale')

@section('content')
    <div class="pos-layout">

    <!-- LEFT CATEGORY SIDEBAR -->
    @include('pos.partials.left-sidebar')

    <!-- MAIN PRODUCT AREA -->
    <main class="main-content">

        @include('pos.main-content')

    </main>

    <!-- RIGHT CART -->
    @include('pos.partials.right-sidebar')

  </div>

  <!-- BOTTOM ACTION BAR -->
  @include('pos.partials.bottom-buttons')

  <!-- PAYMENT MODAL -->
  @include('pos.partials.payment-modal')
@endsection

@push('scripts')
    @include('pos.partials.javascript')
@endpush