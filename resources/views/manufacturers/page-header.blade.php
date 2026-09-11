{{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            @if(request()->routeIs('manufacturers.create'))
                <i class="fas fa-plus-circle text-success me-2"></i>
                Create New Manufacturer
            @elseif(request()->routeIs('manufacturers.edit'))
                <i class="fas fa-edit text-warning me-2"></i>
                Edit Manufacturer
            @elseif(request()->routeIs('manufacturers.show'))
                <i class="fas fa-eye text-info me-2"></i>
                Manufacturer Details
            @endif
        </h2>
        <p class="text-muted mb-0">
            @if(request()->routeIs('manufacturers.create'))
                Add a new manufacturer to the system.
            @elseif(request()->routeIs('manufacturers.edit'))
                Update manufacturer details.
            @elseif(request()->routeIs('manufacturers.show'))
                View manufacturer information.
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
                    <a href="{{ route('manufacturers.index') }}">Manufacturers</a>
                </li>
                <li class="breadcrumb-item active">
                    @if(request()->routeIs('manufacturers.create'))
                        Create
                    @elseif(request()->routeIs('manufacturers.edit'))
                        Edit
                    @elseif(request()->routeIs('manufacturers.show'))
                        Details
                    @endif
                </li>
            </ol>
        </nav>
    </div>
</div>