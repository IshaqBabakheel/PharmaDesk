@extends('layouts.app')

@section('title', 'Backups')

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-database text-primary me-2"></i>
                    Backups
                </h3>
                <p class="text-muted mb-0">
                    Protect PharmaDesk database and application data.
                </p>
            </div>


            @can('backups.create')
                <form method="POST" action="{{ route('backups.create') }}" id="createBackupForm">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="createBackupBtn">
                        <i class="fas fa-database me-1"></i>
                        Create Backup
                    </button>
                </form>
            @endcan
        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Backup History
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Backup</th>
                                <th>Date</th>
                                <th>Size</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($backups as $index => $backup)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <i class="fas fa-file-zipper text-primary me-1"></i>
                                        {{ $backup['filename'] }}
                                    </td>
                                    <td>{{ $backup['date'] ?? '-' }}</td>
                                    <td>{{ $backup['size_human'] }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @can('backups.download')
                                                <a href="{{ route('backups.download', $backup['filename']) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endcan

                                            @can('backups.delete')
                                                <form method="POST"
                                                    action="{{ route('backups.destroy', $backup['filename']) }}"
                                                    class="backup-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-database fs-1 d-block mb-2"></i>
                                        No backups found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(function() {

            $('#createBackupForm')
                .on('submit', function() {

                    const button =
                        $('#createBackupBtn');

                    button
                        .prop('disabled', true)
                        .html(`
                    <span
                        class="spinner-border
                        spinner-border-sm
                        me-1">
                    </span>
                    Creating...
                `);

                });


            $('.backup-delete-form')
                .on('submit', function(event) {

                    if (
                        !confirm(
                            'Are you sure you want to delete this backup?'
                        )
                    ) {
                        event.preventDefault();
                    }

                });

        });
    </script>
@endpush
