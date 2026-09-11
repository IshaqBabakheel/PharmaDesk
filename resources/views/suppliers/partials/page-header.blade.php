{{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            @if(request()->routeIs('suppliers.create'))
                <i class="fas fa-plus-circle text-success me-2"></i>
                Create New Supplier
            @elseif(request()->routeIs('suppliers.edit'))
                <i class="fas fa-edit text-warning me-2"></i>
                Edit Supplier
            @elseif(request()->routeIs('suppliers.show'))
                <i class="fas fa-eye text-info me-2"></i>
                Supplier Details
            @endif
        </h2>
        <p class="text-muted mb-0">
            @if(request()->routeIs('suppliers.create'))
                Add a new supplier to the system.
            @elseif(request()->routeIs('suppliers.edit'))
                Update supplier details.
            @elseif(request()->routeIs('suppliers.show'))
                View supplier information.
            @endif
        </p>
    </div>
    <div>
        <nav>
            <ol class="breadcrumb justify-content-end mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('suppliers.index') }}">Suppliers</a>
                </li>
                <li class="breadcrumb-item active">
                    @if(request()->routeIs('suppliers.create'))
                        Create
                    @elseif(request()->routeIs('suppliers.edit'))
                        Edit
                    @elseif(request()->routeIs('suppliers.show'))
                        Details
                    @endif
                </li>
            </ol>
        </nav>
    </div>
</div>