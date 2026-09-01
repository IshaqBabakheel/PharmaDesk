@php
    $actionName = match ($module) {
        'sales' => $row->invoice_number,
        'purchases' => $row->purchase_number,
        'sale-returns',
        'purchase-returns' => $row->return_number,
        default => $row->name ?? $row->id,
    };
@endphp

<div class="dropdown">

    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-ellipsis-v me-1"></i>
        Actions
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow">

        {{-- View --}}
        @if ($show ?? false)
            @can($module . '.view')
                <li>
                    <a href="{{ route($module . '.show', $row) }}" class="dropdown-item">
                        <i class="fas fa-eye text-info me-2"></i>
                        View Details
                    </a>
                </li>
            @endcan
        @endif


        {{-- Edit --}}
        @if ($edit ?? false)
            @can($module . '.edit')
                <li>
                    <a href="{{ route($module . '.edit', $row) }}" class="dropdown-item">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Edit
                    </a>
                </li>
            @endcan
        @endif


        {{-- Complete --}}
        @if ($complete ?? false)
            @can($module . '.complete')
                <li>
                    <form action="{{ route($module . '.complete', $row) }}" method="POST" class="complete-form"
                        data-name="{{ $actionName }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="dropdown-item text-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Complete
                        </button>
                    </form>
                </li>
            @endcan
        @endif

        {{-- Purchase Return --}}
        @if ($purchaseReturn ?? false)
            @can('purchase-returns.create')
                <li>
                    <a href="{{ route('purchase-returns.create', ['purchase' => $row->id]) }}"
                        class="dropdown-item text-danger">
                        <i class="fas fa-rotate-left me-2"></i>
                        Purchase Return
                    </a>
                </li>
            @endcan
        @endif


        {{-- Cancel --}}
        @if ($cancel ?? false)
            @can($module . '.cancel')
                <li>
                    <form action="{{ route($module . '.cancel', $row) }}" method="POST" class="cancel-form"
                        data-name="{{ $actionName }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-ban me-2"></i>
                            Cancel
                        </button>
                    </form>
                </li>
            @endcan
        @endif

        {{-- Sale Return --}}
        @if ($saleReturn ?? false)
            @can('sale-returns.create')
                <li>
                    <a href="{{ route('sale-returns.create', ['sale' => $row->id]) }}"
                        class="dropdown-item text-danger">
                        <i class="fas fa-rotate-left me-2"></i>
                        Sale Return
                    </a>
                </li>
            @endcan
        @endif

        {{-- Update Payment --}}
        @if ($updatePayment ?? false)
            @can($module . '.update-payment')
                <li>
                    <button type="button" class="dropdown-item text-primary update-payment-btn"
                        data-id="{{ $row->id }}" 
                        data-name="{{ $module === 'purchases'
                            ? $row->purchase_number
                            : $row->invoice_number }}"
                        data-paid="{{ $row->paid_amount }}" data-total="{{ $row->grand_total }}">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Update Payment
                    </button>
                </li>
            @endcan
        @endif


        @if (($stockHistory ?? false) || ($purchaseHistory ?? false) || ($salesHistory ?? false))
            <li>
                <hr class="dropdown-divider">
            </li>
        @endif

        {{-- Stock History --}}
        @if ($stockHistory ?? false)
            @can($module . '.view')
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-clock-rotate-left text-primary me-2"></i>
                        Stock History
                    </a>
                </li>
            @endcan
        @endif


        {{-- Purchase History --}}
        @if ($purchaseHistory ?? false)
            @can($module . '.view')
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cart-shopping text-success me-2"></i>
                        Purchase History
                    </a>
                </li>
            @endcan
        @endif


        {{-- Sales History --}}
        @if ($salesHistory ?? false)
            @can($module . '.view')
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cash-register text-secondary me-2"></i>
                        Sales History
                    </a>
                </li>
            @endcan
        @endif


        @if (
                ($receipt ?? false) ||
                ($barcode ?? false) ||
                ($duplicate ?? false) ||
                ($restore ?? false) ||
                ($forceDelete ?? false) ||
                ($delete ?? false)
            )
            <li>
                <hr class="dropdown-divider">
            </li>
        @endif


        {{-- Print Barcode --}}
        @if ($barcode ?? false)
            @can($module . '.print')
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-barcode text-dark me-2"></i>
                        Print Barcode
                    </a>
                </li>
            @endcan
        @endif

        {{-- Print Receipt --}}
        @if ($receipt ?? false)

            @can($module . '.view')

                <li>

                    <a
                        href="{{ route(
                            'sales.receipt.thermal',
                            $row
                        ) }}"
                        target="_blank"
                        class="dropdown-item"
                    >
                        <i class="fas fa-receipt text-primary me-2"></i>
                        Print Receipt
                    </a>

                </li>

            @endcan

        @endif


        {{-- Duplicate --}}
        @if ($duplicate ?? false)
            @can($module . '.create')
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-copy text-primary me-2"></i>
                        Duplicate
                    </a>
                </li>
            @endcan
        @endif


        {{-- Restore --}}
        @if ($restore ?? false)
            @can($module . '.restore')
                <li>
                    <form action="{{ route($module . '.restore', $row->id) }}" method="POST" class="restore-form"
                        data-name="{{ $row->name }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="dropdown-item text-success">
                            <i class="fas fa-rotate-left me-2"></i>
                            Restore
                        </button>
                    </form>
                </li>
            @endcan
        @endif


        {{-- Force Delete --}}
        @if ($forceDelete ?? false)
            @can($module . '.force-delete')
                <li>
                    <form action="{{ route($module . '.force-delete', $row->id) }}" method="POST"
                        class="force-delete-form" data-name="{{ $row->name }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-trash-can me-2"></i>
                            Permanently Delete
                        </button>
                    </form>
                </li>
            @endcan
        @endif


        {{-- Delete --}}
        @if ($delete ?? false)
            @can($module . '.delete')
                <li>
                    <form action="{{ route($module . '.destroy', $row) }}" method="POST" class="delete-form"
                        data-name="{{ $row->name }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-trash me-2"></i>
                            Delete
                        </button>
                    </form>
                </li>
            @endcan
        @endif

    </ul>

</div>
