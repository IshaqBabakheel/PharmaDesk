<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PharmaDesk POS</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- pos css --}}
  <link rel="stylesheet" href="{{asset('css/pos.css')}}">
  <link rel="stylesheet" href="{{asset('css/full-screen.css')}}">
  <style>
    /* =========================================================
       POS TOAST
    ========================================================= */

    #posToastContainer {
        width: 360px;
        max-width: calc(100vw - 24px);
    }

    .pos-toast {
        border: 0;
        border-radius: 10px;

        box-shadow:
            0 8px 25px rgba(20, 35, 55, .15);

        overflow: hidden;
    }

    .pos-toast .toast-body {
        padding: 12px 14px;

        font-size: .82rem;
        font-weight: 600;

        display: flex;
        align-items: center;

        gap: 9px;
    }

    .pos-toast-icon {
        width: 26px;
        height: 26px;

        border-radius: 50%;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 auto;

        font-size: .82rem;
    }


    /* =========================================================
       SUCCESS
    ========================================================= */

    .pos-toast-success {
        background: #85eaba;
        border-left: 4px solid #198754;
        color: #075b2d;
    }

    .pos-toast-success .pos-toast-icon {
        background: #dff5e8;
        color: #198754;
    }


    /* =========================================================
       DANGER / ERROR
    ========================================================= */

    .pos-toast-danger {
        background: #f8b4bc;
        border-left: 4px solid #dc3545;
        color: #7a1721;
    }

    .pos-toast-danger .pos-toast-icon {
        background: #fde4e7;
        color: #dc3545;
    }


    /* =========================================================
       WARNING
    ========================================================= */

    .pos-toast-warning {
        background: #ffe39a;
        border-left: 4px solid #f0ad00;
        color: #704f00;
    }

    .pos-toast-warning .pos-toast-icon {
        background: #fff1cc;
        color: #b77900;
    }


    /* =========================================================
       INFO
    ========================================================= */

    .pos-toast-info {
        background: #a9c9ff;
        border-left: 4px solid #2563c9;
        color: #123d78;
    }

    .pos-toast-info .pos-toast-icon {
        background: #e3edff;
        color: #2563c9;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 650px) {

        #posToastContainer {
            top: 10px !important;
            right: 10px !important;
            left: 10px !important;

            width: auto;
        }
    }
  </style>
  @stack('styles')
</head>

<body>

  @yield('content')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  {{-- Chart library --}} 
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="{{asset('js/full-screen.js')}}"></script>

  <!-- =========================================================
     POS TOAST CONTAINER
     ========================================================= -->

<div
    class="toast-container position-fixed top-0 end-0 p-3"
    id="posToastContainer"
    style="z-index: 9999;">
</div>

  @stack('scripts')
</body>
</html>
