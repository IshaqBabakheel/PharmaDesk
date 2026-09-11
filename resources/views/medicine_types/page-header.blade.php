{{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            @if(request()->routeIs('medicine-types.create'))
                <i class="fas fa-plus-circle text-success me-2"></i>
                Create New Type
            @elseif(request()->routeIs('medicine-types.edit'))
                <i class="fas fa-edit text-warning me-2"></i>
                Edit Type
            @elseif(request()->routeIs('medicine-types.show'))
                <i class="fas fa-eye text-info me-2"></i>
                Type Details
            @endif
        </h2>
        <p class="text-muted mb-0">
            @if(request()->routeIs('medicine-types.create'))
                Add a new medicine type to the system.
            @elseif(request()->routeIs('medicine-types.edit'))
                Update medicine type details.
            @elseif(request()->routeIs('medicine-types.show'))
                View medicine type information.
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
                    <a href="{{ route('medicine-types.index') }}">Types</a>
                </li>
                <li class="breadcrumb-item active">
                    @if(request()->routeIs('medicine-types.create'))
                        Create
                    @elseif(request()->routeIs('medicine-types.edit'))
                        Edit
                    @elseif(request()->routeIs('medicine-types.show'))
                        Details
                    @endif
                </li>
            </ol>
        </nav>
    </div>
</div>