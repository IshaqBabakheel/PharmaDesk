@extends('layouts.app')

@section('title', 'Inventory Report')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-boxes-stacked text-primary me-2"></i>
                    Inventory Report
                </h3>
                <p class="text-muted mb-0">
                    Medicine-level stock, valuation and expiry overview.
                </p>
            </div>

            <div>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb justify-content-end mb-2">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home') }}">
                            <i class="fas fa-house me-1"></i>
                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        Inventory Reports

                    </li>

                </ol>

            </nav>

        </div>
        </div>
        @include('inventory_reports.partials.stats')
        @include('inventory_reports.partials.filters')
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-pills me-2 text-primary"></i>
                    Inventory
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="inventoryReportTable" class="table table-bordered table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Category</th>
                                <th>Batches</th>
                                <th>Stock</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Stock Cost</th>
                                <th>Retail Value</th>
                                <th>Potential Profit</th>
                                <th>Nearest Expiry</th>
                                <th>Status</th>
                                <th>Action</th>
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
            function stockBadge(status) {
                switch (status) {
                    case 'out': return ` <span class="badge bg-danger"> Out of Stock </span> `;
                    case 'low': return ` <span class="badge bg-warning text-dark"> Low Stock </span> `;
                    default: return ` <span class="badge bg-success"> In Stock </span> `;
                }
            }

            function expiryBadge(date) {
                if (!date || date === '-') {
                    return '-';
                }
                const expiry = new Date(date + 'T00:00:00');
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const days = Math.ceil((expiry - today) / 86400000);
                if (days < 0) {
                    return ` <span class="badge bg-danger"> ${date} </span> `;
                }
                if (days <= 30) {
                    return ` <span class="badge bg-warning text-dark"> ${date} </span> `;
                }
                return ` <span> ${date} </span> `;
            }
            const table = $('#inventoryReportTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: {
                    url: "{{ route('inventory-reports.datatable') }}",
                    data: function(d) {
                        d.medicine_id = $('#medicineFilter').val();
                        d.medicine_category_id = $('#categoryFilter').val();
                        d.stock_status = $('#stockStatusFilter').val();
                    },
                    dataSrc: function(json) {
                        loadInventoryStatistics();
                        return json.data;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'medicine',
                        render: function(data, type, row) {
                            if (type !== 'display') {
                                return data;
                            }
                            const url =
                                "{{ route('inventory-reports.medicine', ['medicineId' => '__ID__']) }}"
                                .replace('__ID__', row.medicine_id);
                            return ` <a href="${url}" class="fw-semibold text-decoration-none" > ${data} </a> `;
                        }
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'batch_count',
                        className: 'text-center'
                    },
                    {
                        data: 'batch_stock',
                        className: 'text-end fw-semibold'
                    },
                    {
                        data: 'purchase_price',
                        className: 'text-end'
                    },
                    {
                        data: 'selling_price',
                        className: 'text-end'
                    },
                    {
                        data: 'stock_cost',
                        className: 'text-end'
                    },
                    {
                        data: 'retail_value',
                        className: 'text-end'
                    },
                    {
                        data: 'potential_profit',
                        className: 'text-end text-success fw-semibold'
                    },
                    {
                        data: 'nearest_expiry',
                        render: function(data) {
                            return expiryBadge(data);
                        }
                    },
                    {
                        data: 'stock_status',
                        render: function(data) {
                            return stockBadge(data);
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const url =
                                "{{ route('inventory-reports.medicine', ['medicineId' => '__ID__']) }}"
                                .replace('__ID__', row.medicine_id);
                            return ` <a href="${url}" class="btn btn-sm btn-info" title="View Inventory Details" > <i class="fas fa-eye"></i> </a> `;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ]
            });
            $('#medicineFilter, #categoryFilter, #stockStatusFilter').on('change', function() {
                table.ajax.reload();
            });
            $('#resetInventoryFilters').on('click', function() {
                $('#medicineFilter').val('');
                $('#categoryFilter').val('');
                $('#stockStatusFilter').val('all');
                table.ajax.reload();
            });

            function loadInventoryStatistics() {
                $.ajax({
                    url: "{{ route('inventory-reports.statistics') }}",
                    type: 'GET',
                    data: {
                        medicine_id: $('#medicineFilter').val(),
                        medicine_category_id: $('#categoryFilter').val(),
                        stock_status: $('#stockStatusFilter').val(),
                    },
                    success: function(response) {
                        if (!response.success || !response.statistics) {
                            return;
                        }
                        const stats = response.statistics;
                        $('#statMedicines').text(Number(stats.medicines || 0).toLocaleString());
                        $('#statStock').text(Number(stats.total_stock || 0).toLocaleString());
                        $('#statCost').text('Rs. ' + Number(stats.stock_cost || 0).toLocaleString(
                            'en-PK', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                        $('#statRetail').text('Rs. ' + Number(stats.retail_value || 0).toLocaleString(
                            'en-PK', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                        $('#statProfit').text('Rs. ' + Number(stats.potential_profit || 0)
                            .toLocaleString('en-PK', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                        $('#statLowStock').text(Number(stats.low_stock || 0).toLocaleString());
                        $('#statOutStock').text(Number(stats.out_of_stock || 0).toLocaleString());
                    }
                });
            }
        });
    </script>
@endpush
