<script>
    $(document).ready(function() {
        /*
        |--------------------------------------------------------------------------
        | Status Badge
        |--------------------------------------------------------------------------
        */
        function expiryStatusBadge(status) {
            switch (status) {
                case 'expired':
                    return `
                        <span class="badge bg-danger">
                            <i class="fas fa-circle-exclamation me-1"></i>
                            Expired
                        </span>
                    `;


                case 'critical':
                    return `
                        <span class="badge bg-danger">
                            <i class="fas fa-triangle-exclamation me-1"></i>
                            Critical
                        </span>
                    `;


                case 'warning':
                    return `
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>
                            Expiring Soon
                        </span>
                    `;


                case 'upcoming':
                    return `
                        <span class="badge bg-info">
                            <i class="fas fa-calendar-days me-1"></i>
                            Upcoming
                        </span>
                    `;


                default:
                    return `
                        <span class="badge bg-success">
                            <i class="fas fa-circle-check me-1"></i>
                            Safe
                        </span>
                    `;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Convert Days to Human Readable
        |--------------------------------------------------------------------------
        */
        function daysToHumanReadable(days) {
            const absDays = Math.abs(days);
            const parts = [];
            let remaining = absDays;

            // Years
            const years = Math.floor(remaining / 365);
            if (years > 0) {
                parts.push(`${years} year${years > 1 ? 's' : ''}`);
                remaining = remaining % 365;
            }

            // Months (30 days approximation)
            const months = Math.floor(remaining / 30);
            if (months > 0) {
                parts.push(`${months} month${months > 1 ? 's' : ''}`);
                remaining = remaining % 30;
            }

            // Weeks
            const weeks = Math.floor(remaining / 7);
            if (weeks > 0) {
                parts.push(`${weeks} week${weeks > 1 ? 's' : ''}`);
                remaining = remaining % 7;
            }

            // Days
            if (remaining > 0) {
                parts.push(`${remaining} day${remaining > 1 ? 's' : ''}`);
            }

            return parts.length > 0 ? parts.join(' ') : '0 days';
        }

        /*
        |--------------------------------------------------------------------------
        | Format Days Remaining with Colors
        |--------------------------------------------------------------------------
        */
        function formatDaysRemaining(days) {
            days = parseInt(days, 10);

            if (isNaN(days)) {
                return '-';
            }

            if (days === 0) {
                return '<span class="text-danger fw-semibold">Expires today</span>';
            }

            const humanReadable = daysToHumanReadable(days);

            if (days < 0) {
                return `<span class="text-danger fw-semibold">Expired ${humanReadable} ago</span>`;
            }

            // Future expiry with color coding
            if (days <= 7) {
                return `<span class="text-danger fw-semibold">${humanReadable}</span>`;
            }

            if (days <= 30) {
                return `<span class="text-warning fw-semibold">${humanReadable}</span>`;
            }

            return `<span class="text-muted">${humanReadable}</span>`;
        }


        /*
        |--------------------------------------------------------------------------
        | DataTable
        |--------------------------------------------------------------------------
        */
        const table = $('#expiryTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,

            ajax: {
                url: "{{ route('expiry.datatable') }}",
                data: function(params) {
                    params.filter = $('#expiryFilter').val();
                },
                dataSrc: 'data'
            },


            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },


                {
                    data: 'medicine',
                    name: 'medicine'
                },


                {
                    data: 'batch_number',
                    name: 'batch_number'
                },


                {
                    data: 'expiry_date',
                    name: 'expiry_date'
                },


                {
                    data: 'available_quantity',
                    name: 'available_quantity',
                    className: 'text-end'
                },


                {
                    data: 'days_remaining',
                    name: 'days_remaining',

                    render: function(data) {

                        return formatDaysRemaining(data);
                    }
                },


                {
                    data: 'expiry_status',
                    name: 'expiry_status',

                    render: function(data) {
                        return expiryStatusBadge(data);
                    }
                }

            ],

            order: [
                [3, 'asc']
            ],

            pageLength: 25,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ]

        });


        /*
        |--------------------------------------------------------------------------
        | Expiry Filter
        |--------------------------------------------------------------------------
        */
        $('#expiryFilter').on('change', function() {
            table.ajax.reload();
        });


        /*
        |--------------------------------------------------------------------------
        | Medicine Search
        |--------------------------------------------------------------------------
        */
        $('#medicineFilter').on('input', function() {
            table.column(1).search($(this).val()).draw();
        });


        /*
        |--------------------------------------------------------------------------
        | Reset Filters
        |--------------------------------------------------------------------------
        */
        $('#resetExpiryFilters').on('click', function() {
            $('#expiryFilter').val('all');
            $('#medicineFilter').val('');
            table.column(1).search('').draw();
            table.ajax.reload();
        });


        /*
        |--------------------------------------------------------------------------
        | Load Statistics
        |--------------------------------------------------------------------------
        */
        function loadExpiryStatistics() {
            $.ajax({
                url: "{{ route('expiry.statistics') }}",
                type: 'GET',
                success: function(response) {

                    if (!response.success || !response.statistics) {
                        return;
                    }

                    const stats = response.statistics;

                    $('#expiredBatches').text(stats.expired_batches ?? 0);
                    $('#criticalBatches').text(stats.critical_batches ?? 0);
                    $('#warningBatches').text(stats.warning_batches ?? 0);
                    $('#upcomingBatches').text(stats.upcoming_batches ?? 0);
                    $('#totalExpiryBatches').text(stats.total_expiry_batches ?? 0);
                    $('#totalExpiryQuantity').text(Number(stats.total_expiry_quantity ?? 0)
                        .toLocaleString());
                },

                error: function() {
                    console.error('Unable to load expiry statistics.');
                }

            });
        }

        loadExpiryStatistics();
    });
</script>
