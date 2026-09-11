{{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            @if(request()->routeIs('units.create'))
                <i class="fas fa-plus-circle text-success me-2"></i>
                Create New Unit
            @elseif(request()->routeIs('units.edit'))
                <i class="fas fa-edit text-warning me-2"></i>
                Edit Unit
            @elseif(request()->routeIs('units.show'))
                <i class="fas fa-eye text-info me-2"></i>
                Unit Details
            @endif
        </h2>
        <p class="text-muted mb-0">
            @if(request()->routeIs('units.create'))
                Add a new measurement unit to the system.
            @elseif(request()->routeIs('units.edit'))
                Update unit details.
            @elseif(request()->routeIs('units.show'))
                View unit information.
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
                    <a href="{{ route('units.index') }}">Units</a>
                </li>
                <li class="breadcrumb-item active">
                    @if(request()->routeIs('units.create'))
                        Create
                    @elseif(request()->routeIs('units.edit'))
                        Edit
                    @elseif(request()->routeIs('units.show'))
                        Details
                    @endif
                </li>
            </ol>
        </nav>
    </div>
</div>