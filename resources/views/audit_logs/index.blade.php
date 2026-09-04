@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="mb-1">Activity Logs</h4>
                <p class="text-muted mb-0">
                    Monitor important user and system activity.
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label for="filter_user" class="form-label">User</label>
                        <select id="filter_user" class="form-select">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                    @if ($user->email)
                                        — {{ $user->email }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="filter_event" class="form-label">Action</label>
                        <select id="filter_event" class="form-select">
                            <option value="">All Actions</option>
                            @foreach ($events as $event)
                                <option value="{{ $event }}">
                                    {{ ucfirst(str_replace('_', ' ', $event)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="filter_log_name" class="form-label">Log</label>
                        <select id="filter_log_name" class="form-select">
                            <option value="">All Logs</option>
                            @foreach ($logNames as $logName)
                                <option value="{{ $logName }}">
                                    {{ ucfirst(str_replace('_', ' ', $logName)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="filter_subject_type" class="form-label">Module</label>
                        <select id="filter_subject_type" class="form-select">
                            <option value="">All Modules</option>
                            @foreach ($subjectTypes as $subjectType)
                                <option value="{{ $subjectType }}">
                                    {{ class_basename($subjectType) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="filter_search" class="form-label">Search</label>
                        <input type="text" id="filter_search" class="form-control" placeholder="Search activity...">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="filter_date_from" class="form-label">
                            Date From
                        </label>
                        <input type="date" id="filter_date_from" class="form-control">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="filter_date_to" class="form-label">
                            Date To
                        </label>
                        <input type="date" id="filter_date_to" class="form-control">
                    </div>

                    <div class="col-lg-8 col-md-12 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-primary" id="applyFilters">
                            <i class="bi bi-funnel me-1"></i>
                            Apply Filters
                        </button>

                        <button type="button" class="btn btn-light border" id="resetFilters">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </button>
                    </div>

                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="activityLogsTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Record</th>
                                <th>Description</th>
                                <th>Log</th>
                                <th class="text-end">View</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const table = $('#activityLogsTable').DataTable({
                processing: true,
                serverSide: false,

                ajax: {
                    url: @json(route('audit-logs.datatable')),
                    data: function(data) {
                        data.user_id = $('#filter_user').val();
                        data.event = $('#filter_event').val();
                        data.log_name = $('#filter_log_name').val();
                        data.subject_type = $('#filter_subject_type').val();
                        data.date_from = $('#filter_date_from').val();
                        data.date_to = $('#filter_date_to').val();
                        data.search = $('#filter_search').val();
                    }
                },

                /*
                 * Sort by the hidden numeric timestamp column.
                 * This guarantees newest activities appear first.
                 */
                order: [
                    [1, 'desc']
                ],

                pageLength: 25,

                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],

                columns: [{
                        data: 'date',
                        orderable: false
                    },

                    {
                        data: 'date_sort',
                        visible: false,
                        searchable: false
                    },

                    {
                        data: 'user',
                        render: function(data) {
                            return escapeHtml(data || 'System');
                        }
                    },

                    {
                        data: 'event',
                        render: function(data) {
                            const label = formatEvent(data);
                            const badge = eventBadge(data);

                            return `
                        <span class="badge ${badge}">
                            ${escapeHtml(label)}
                        </span>
                    `;
                        }
                    },

                    {
                        data: 'module',
                        render: function(data) {
                            return escapeHtml(data || 'System');
                        }
                    },

                    {
                        data: 'record',
                        render: function(data) {
                            return escapeHtml(data || '—');
                        }
                    },

                    {
                        data: 'description',
                        render: function(data) {
                            return escapeHtml(data || '');
                        }
                    },

                    {
                        data: 'log_name',
                        render: function(data) {
                            return escapeHtml(
                                formatEvent(data || 'default')
                            );
                        }
                    },

                    {
                        data: 'view_url',
                        className: 'text-end',
                        orderable: false,
                        searchable: false,
                        render: function(url) {
                            return `
                        <a
                            href="${escapeAttribute(url)}"
                            class="btn btn-sm btn-outline-primary"
                            title="View Activity"
                        >
                            <i class="fas fa-eye"></i>
                        </a>
                    `;
                        }
                    }
                ]
            });

            $('#applyFilters').on('click', function() {
                table.ajax.reload();
            });

            $('#resetFilters').on('click', function() {
                $('#filter_user').val('');
                $('#filter_event').val('');
                $('#filter_log_name').val('');
                $('#filter_subject_type').val('');
                $('#filter_date_from').val('');
                $('#filter_date_to').val('');
                $('#filter_search').val('');

                table.ajax.reload();
            });

            $('#filter_search').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    table.ajax.reload();
                }
            });

            function formatEvent(value) {
                return String(value || 'activity')
                    .replaceAll('_', ' ')
                    .replace(/\b\w/g, letter => letter.toUpperCase());
            }

            function eventBadge(event) {
                event = String(event || '').toLowerCase();

                if (['created', 'completed', 'login', 'restored'].includes(event)) {
                    return 'bg-success';
                }

                if (['updated', 'recorded'].includes(event)) {
                    return 'bg-primary';
                }

                if (['deleted', 'cancelled', 'login_failed'].includes(event)) {
                    return 'bg-danger';
                }

                if (['payment', 'payment_recorded'].includes(event)) {
                    return 'bg-info text-dark';
                }

                return 'bg-secondary';
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function escapeAttribute(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
            }
        });
    </script>
@endpush
