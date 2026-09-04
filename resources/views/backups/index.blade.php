@extends('layouts.app')

@push('styles')
    <style>
        .backup-table-filename {
            max-width: 360px;
            word-break: break-word;
        }

        .swal2-html-container {
            font-size: .95rem;
        }
    </style>
@endpush

@section('title', 'Backups')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

            <div>
                <h3 class="mb-1">
                    <i class="fas fa-database text-primary me-2"></i>
                    Backup & Disaster Recovery
                </h3>

                <p class="text-muted mb-0">
                    Protect PharmaDesk database and application data.
                </p>
            </div>

            <div class="d-flex gap-2">

                @can('backups.cleanup')
                    <button type="button" id="cleanupBackupsBtn" class="btn btn-outline-secondary"
                        data-url="{{ route('backups.cleanup') }}">

                        <i class="fas fa-broom me-1"></i>
                        Run Cleanup

                    </button>
                @endcan

                @can('backups.create')
                    <button type="button" id="createBackupBtn" class="btn btn-primary">

                        <i class="fas fa-database me-1"></i>
                        Create Backup

                    </button>
                @endcan

            </div>

        </div>


        {{-- Dashboard Statistics --}}
        <div class="row g-3 mb-4">

            {{-- Total backups --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Total Backups
                                </p>

                                <h3 class="mb-0">
                                    {{ $statistics['total_backups'] }}
                                </h3>

                            </div>

                            <div class="text-primary fs-2">
                                <i class="fas fa-database"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Storage --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Storage Used
                                </p>

                                <h3 class="mb-0">
                                    {{ $statistics['total_storage'] }}
                                </h3>

                            </div>

                            <div class="text-info fs-2">
                                <i class="fas fa-hard-drive"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Latest --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Latest Backup
                                </p>

                                <h6 class="mb-1">

                                    {{ $statistics['latest_backup']['date'] ?? 'No backup' }}

                                </h6>

                                @if (!empty($statistics['latest_backup']['age_human']))
                                    <small class="text-muted">
                                        {{ $statistics['latest_backup']['age_human'] }}
                                    </small>
                                @endif

                            </div>

                            <div class="text-success fs-2">
                                <i class="fas fa-clock"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Latest size --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Latest Size
                                </p>

                                <h3 class="mb-0">
                                    {{ $statistics['latest_size'] }}
                                </h3>

                            </div>

                            <div class="text-warning fs-2">
                                <i class="fas fa-file-zipper"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Health --}}
        @php
            $health = $statistics['health'];
        @endphp

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <h5 class="mb-1">
                            Backup Health
                        </h5>

                        <p class="text-muted mb-0">
                            {{ $health['message'] }}
                        </p>

                    </div>

                    <div>

                        @if ($health['status'] === 'healthy')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>
                                Healthy
                            </span>
                        @elseif ($health['status'] === 'warning')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Warning
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="fas fa-circle-exclamation me-1"></i>
                                Critical
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Backup information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-lg-8">

                        <h5 class="mb-3">
                            <i class="fas fa-shield-halved text-primary me-2"></i>
                            What does a PharmaDesk backup protect?
                        </h5>

                        <p class="text-muted">
                            PharmaDesk backups provide a recovery point for important
                            pharmacy business data and the configured application files.
                        </p>

                        <div class="row g-2">

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Database
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Application Files
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="alert alert-warning mb-0">

                            <strong>
                                <i class="fas fa-triangle-exclamation me-1"></i>
                                Recovery Tip
                            </strong>

                            <div class="small mt-2">
                                Keep at least one backup outside the machine where
                                PharmaDesk is running.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Backup History --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        Backup History
                    </h5>

                    @if ($statistics['total_backups'] > 0)
                        <span class="text-muted small">
                            {{ $statistics['total_backups'] }} recovery point(s)
                        </span>
                    @endif

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Backup</th>

                                <th>Created</th>

                                <th>Age</th>

                                <th>Size</th>

                                <th>Disk</th>

                                <th>Status</th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($backups as $index => $backup)
                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="me-2 text-primary">
                                                <i class="fas fa-file-zipper"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold text-break">
                                                    {{ $backup['filename'] }}
                                                </div>

                                                @if (!empty($backup['is_latest']))
                                                    <span class="badge bg-primary">
                                                        Latest
                                                    </span>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    <td>
                                        {{ $backup['date'] ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $backup['age_human'] ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $backup['size_human'] }}
                                    </td>


                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $backup['disk'] }}
                                        </span>
                                    </td>


                                    <td>

                                        @if ($backup['exists'])
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="fas fa-check me-1"></i>
                                                Available
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-xmark me-1"></i>
                                                Missing
                                            </span>
                                        @endif

                                    </td>


                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            @can('backups.details')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary backup-details-btn"
                                                    data-filename="{{ $backup['filename'] }}"
                                                    data-url="{{ route('backups.show', $backup['filename']) }}"
                                                    title="View details">

                                                    <i class="fas fa-eye"></i>

                                                </button>
                                            @endcan


                                            @can('backups.download')
                                                <a href="{{ route('backups.download', $backup['filename']) }}"
                                                    class="btn btn-sm btn-outline-success" title="Download">

                                                    <i class="fas fa-download"></i>

                                                </a>
                                            @endcan


                                            @can('backups.restore')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-warning backup-restore-btn"
                                                    data-filename="{{ $backup['filename'] }}"
                                                    data-url="{{ route('backups.restore', $backup['filename']) }}"
                                                    title="Restore database">

                                                    <i class="fas fa-rotate-left"></i>

                                                </button>
                                            @endcan


                                            @can('backups.delete')
                                                <button type="button" class="btn btn-sm btn-outline-danger backup-delete-btn"
                                                    data-filename="{{ $backup['filename'] }}"
                                                    data-url="{{ route('backups.destroy', $backup['filename']) }}"
                                                    data-latest="{{ !empty($backup['is_latest']) ? '1' : '0' }}"
                                                    title="Delete">

                                                    <i class="fas fa-trash"></i>

                                                </button>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="fas fa-database fa-3x mb-3"></i>

                                            <h5>
                                                No backups available
                                            </h5>

                                            <p class="mb-3">
                                                Your pharmacy data does not currently
                                                have a recovery point.
                                            </p>

                                            @can('backups.create')
                                                <button type="button" class="btn btn-primary" id="emptyCreateBackupBtn">

                                                    <i class="fas fa-database me-1"></i>
                                                    Create First Backup

                                                </button>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Backup details area --}}
        <div id="backupDetailsContainer"></div>

    </div>

@endsection

@push('scripts')
    <script>
        $(function() {

            const csrfToken =
                $('meta[name="csrf-token"]').attr('content') ||
                '{{ csrf_token() }}';


            /*
             * Escape user-controlled values before inserting into HTML.
             */
            function escapeHtml(value) {

                return $('<div>')
                    .text(value ?? '')
                    .html();

            }


            /*
             * CREATE BACKUP
             */
            function createBackup() {

                Swal.fire({
                    title: 'Creating Backup',
                    html: `
                <div class="text-muted">
                    PharmaDesk is creating a backup.
                    <br>
                    Please do not close or refresh this page.
                </div>
            `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: function() {

                        Swal.showLoading();

                    }
                });


                $('#createBackupBtn, #emptyCreateBackupBtn')
                    .prop('disabled', true);


                $.ajax({

                    url: '{{ route('backups.create') }}',

                    type: 'POST',

                    data: {
                        _token: csrfToken
                    },

                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Backup Created',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(function() {

                            window.location.reload();

                        });

                    },

                    error: function(xhr) {

                        const message =
                            xhr.responseJSON?.message ||
                            'Unable to create backup.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Backup Failed',
                            text: message
                        });

                    },

                    complete: function() {

                        $('#createBackupBtn, #emptyCreateBackupBtn')
                            .prop('disabled', false);

                    }

                });

            }


            $('#createBackupBtn')
                .on('click', createBackup);


            $(document)
                .on('click', '#emptyCreateBackupBtn', createBackup);


            /*
             * VIEW DETAILS
             */
            $(document).on(
                'click',
                '.backup-details-btn',
                function() {

                    const button =
                        $(this);

                    const filename =
                        button.data('filename');

                    const url =
                        button.data('url');


                    Swal.fire({
                        title: 'Loading Backup Details...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: function() {

                            Swal.showLoading();

                        }
                    });


                    $.ajax({

                        url: url,

                        type: 'GET',

                        success: function(response) {

                            const data =
                                response.data;


                            Swal.fire({

                                title: 'Backup Details',

                                html: `

                            <div class="text-start">

                                <div class="mb-3">
                                    <div class="text-muted small">
                                        Filename
                                    </div>

                                    <div class="fw-semibold text-break">
                                        ${escapeHtml(data.filename)}
                                    </div>
                                </div>


                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <div class="border rounded p-3">
                                            <div class="text-muted small">
                                                Created
                                            </div>

                                            <div class="fw-semibold">
                                                ${escapeHtml(data.date || '-')}
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="border rounded p-3">
                                            <div class="text-muted small">
                                                Size
                                            </div>

                                            <div class="fw-semibold">
                                                ${escapeHtml(data.size_human)}
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="border rounded p-3">
                                            <div class="text-muted small">
                                                Disk
                                            </div>

                                            <div class="fw-semibold">
                                                ${escapeHtml(data.disk)}
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="border rounded p-3">
                                            <div class="text-muted small">
                                                Age
                                            </div>

                                            <div class="fw-semibold">
                                                ${escapeHtml(data.age_human || '-')}
                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <hr>


                                <div class="mb-2">

                                    <strong>
                                        Backup Contents
                                    </strong>

                                </div>


                                <div class="mb-2">

                                    ${
                                        data.database_included
                                        ? `
                                                <div class="text-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Database
                                                </div>
                                              `
                                        :
                                          `
                                                <div class="text-danger">
                                                    <i class="fas fa-xmark me-1"></i>
                                                    Database
                                                </div>
                                              `
                                    }


                                    ${
                                        data.application_files_included
                                        ? `
                                                <div class="text-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Application Files
                                                </div>
                                              `
                                        :
                                          `
                                                <div class="text-danger">
                                                    <i class="fas fa-xmark me-1"></i>
                                                    Application Files
                                                </div>
                                              `
                                    }

                                </div>


                                <div class="alert alert-info mb-0">

                                    <i class="fas fa-circle-info me-1"></i>

                                    Restore applies to the database.
                                    Application files are available in
                                    the downloaded full backup archive.

                                </div>

                            </div>

                        `,

                                width: 650,

                                confirmButtonText: 'Close'

                            });

                        },

                        error: function(xhr) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Unable to Load Details',
                                text: xhr.responseJSON?.message ||
                                    'Unable to load backup details.'
                            });

                        }

                    });

                }
            );


            /*
             * DELETE
             */
            $(document).on(
                'click',
                '.backup-delete-btn',
                function() {

                    const button =
                        $(this);

                    const filename =
                        button.data('filename');

                    const url =
                        button.data('url');

                    const isLatest =
                        String(
                            button.data('latest')
                        ) === '1';


                    let warning = `
                <p class="mb-2">
                    This backup will be permanently deleted.
                </p>
            `;


                    if (isLatest) {

                        warning += `
                    <div class="alert alert-warning text-start">
                        <i class="fas fa-triangle-exclamation me-1"></i>

                        This is the latest available backup.
                        Deleting it reduces your recovery options.
                    </div>
                `;

                    }


                    Swal.fire({

                        icon: 'warning',

                        title: 'Delete Backup?',

                        html: `
                    <p>
                        You are about to delete:
                    </p>

                    <strong>
                        ${escapeHtml(filename)}
                    </strong>

                    ${warning}
                `,

                        showCancelButton: true,

                        confirmButtonText: 'Delete Backup',

                        cancelButtonText: 'Cancel',

                        confirmButtonColor: '#dc3545',

                        showLoaderOnConfirm: true,

                        preConfirm: function() {

                            return $.ajax({

                                url: url,

                                type: 'DELETE',

                                data: {
                                    _token: csrfToken
                                }

                            }).catch(function(xhr) {

                                Swal.showValidationMessage(
                                    xhr.responseJSON?.message ||
                                    'Unable to delete backup.'
                                );

                                throw xhr;

                            });

                        },

                        allowOutsideClick: function() {

                            return !Swal.isLoading();

                        }

                    }).then(function(result) {

                        if (
                            result.isConfirmed &&
                            result.value?.success
                        ) {

                            Swal.fire({

                                icon: 'success',

                                title: 'Backup Deleted',

                                text: result.value.message,

                                timer: 1600,

                                showConfirmButton: false

                            }).then(function() {

                                window.location.reload();

                            });

                        }

                    });

                }
            );


            /*
             * RESTORE
             */
            $(document).on(
                'click',
                '.backup-restore-btn',
                function() {

                    const button =
                        $(this);

                    const filename =
                        button.data('filename');

                    const url =
                        button.data('url');


                    Swal.fire({

                        icon: 'warning',

                        title: 'Restore Database?',

                        html: `

                    <div class="text-start">

                        <p>
                            You are about to restore:
                        </p>

                        <div class="alert alert-warning">
                            <strong>
                                ${escapeHtml(filename)}
                            </strong>
                        </div>


                        <p class="text-danger fw-bold">
                            WARNING
                        </p>


                        <p>
                            The current PharmaDesk database
                            will be replaced by the selected backup.
                        </p>


                        <p>
                            A fresh safety backup of the current
                            database will be created first.
                        </p>


                        <p class="mb-0">
                            Do not close or refresh the browser
                            while restoration is running.
                        </p>

                    </div>

                `,

                        showCancelButton: true,

                        confirmButtonText: 'Continue',

                        cancelButtonText: 'Cancel',

                        confirmButtonColor: '#fd7e14'

                    }).then(function(firstResult) {

                        if (!firstResult.isConfirmed) {

                            return;

                        }


                        Swal.fire({

                            icon: 'error',

                            title: 'Final Confirmation',

                            html: `

                        <div class="text-start">

                            <p class="fw-bold">
                                This is a destructive operation.
                            </p>

                            <p>
                                Current pharmacy data may be overwritten.
                            </p>

                            <p>
                                Selected backup:
                            </p>

                            <div class="border rounded p-3 mb-3">
                                <strong>
                                    ${escapeHtml(filename)}
                                </strong>
                            </div>

                            <p class="mb-0 fw-bold">
                                Are you absolutely sure?
                            </p>

                        </div>

                    `,

                            showCancelButton: true,

                            confirmButtonText: 'Yes, Restore Database',

                            cancelButtonText: 'Cancel',

                            confirmButtonColor: '#dc3545',

                            showLoaderOnConfirm: true,

                            preConfirm: function() {

                                return $.ajax({

                                    url: url,

                                    type: 'POST',

                                    data: {
                                        _token: csrfToken
                                    }

                                }).catch(function(xhr) {

                                    Swal.showValidationMessage(
                                        xhr.responseJSON?.message ||
                                        'Unable to restore backup.'
                                    );

                                    throw xhr;

                                });

                            },

                            allowOutsideClick: function() {

                                return !Swal.isLoading();

                            }

                        }).then(function(result) {

                            if (
                                result.isConfirmed &&
                                result.value?.success
                            ) {

                                Swal.fire({

                                    icon: 'success',

                                    title: 'Database Restored',

                                    text: result.value.message,

                                    confirmButtonText: 'Reload PharmaDesk'

                                }).then(function() {

                                    window.location.reload();

                                });

                            }

                        });

                    });

                }
            );


            /*
             * CLEANUP
             */
            $('#cleanupBackupsBtn').on(
                'click',
                function() {

                    const button =
                        $(this);

                    const url =
                        button.data('url');


                    Swal.fire({

                        icon: 'warning',

                        title: 'Run Backup Cleanup?',

                        text: 'Older backups will be removed according to the configured retention policy.',

                        showCancelButton: true,

                        confirmButtonText: 'Run Cleanup',

                        cancelButtonText: 'Cancel',

                        confirmButtonColor: '#0d6efd',

                        showLoaderOnConfirm: true,

                        preConfirm: function() {

                            return $.ajax({

                                url: url,

                                type: 'POST',

                                data: {
                                    _token: csrfToken
                                }

                            }).catch(function(xhr) {

                                Swal.showValidationMessage(
                                    xhr.responseJSON?.message ||
                                    'Unable to run cleanup.'
                                );

                                throw xhr;

                            });

                        },

                        allowOutsideClick: function() {

                            return !Swal.isLoading();

                        }

                    }).then(function(result) {

                        if (
                            result.isConfirmed &&
                            result.value?.success
                        ) {

                            Swal.fire({

                                icon: 'success',

                                title: 'Cleanup Complete',

                                text: result.value.message,

                                timer: 1600,

                                showConfirmButton: false

                            }).then(function() {

                                window.location.reload();

                            });

                        }

                    });

                }
            );

        });
    </script>
@endpush
