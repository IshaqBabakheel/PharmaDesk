@php
    $categoryIcons = [
        'Tablet'       => 'bi-tablet',
        'Capsule'      => 'bi-capsule',
        'Syrup'        => 'bi-droplet-half',
        'Injection'    => 'bi-prescription2',
        'Cream'        => 'bi-droplet',
        'Ointment'     => 'bi-bandaid',
        'Gel'          => 'bi-moisture',
        'Drops'        => 'bi-eyedropper',
        'Powder'       => 'bi-cloud',
        'Inhaler'      => 'bi-lungs',
        'Suppository'  => 'bi-capsule-pill',
        'Patch'        => 'bi-stickies',
        'Lotion'       => 'bi-droplet-fill',
        'Spray'        => 'bi-wind',
    ];
@endphp


<aside class="sidebar">

    {{-- Fixed / Sticky Sidebar Header --}}
    <div class="sidebar-header">

        <a
            href="{{ route('home') }}"
            class="dashboard-link"
            aria-label="Back to Dashboard"
        >
            <i class="bi bi-arrow-left"></i>

            <span>Dashboard</span>
        </a>


        <div class="refresh-wrap text-center">

            <button
                type="button"
                class="btn btn-refresh"
                id="refreshBtn"
                title="Refresh Products"
            >
                <i class="bi bi-arrow-repeat me-1"></i>

            </button>

            <button
                type="button"
                class="btn btn-fullscreen"
                id="fullscreenBtn"
                title="Enter Fullscreen (Ctrl + Shift + F)"
            >
                <i class="bi bi-fullscreen"></i>
            </button>

        </div>

    </div>


    {{-- Scrollable Categories --}}
    <div class="category-list">

        {{-- ALL --}}
        <button
            type="button"
            class="category-btn active"
            data-category-id=""
        >
            <i class="bi bi-grid-3x3-gap-fill"></i>

            <span>All</span>
        </button>


        @foreach ($categories as $category)

            @php
                $icon =
                    $categoryIcons[$category->name]
                    ?? 'bi-capsule';
            @endphp


            <button
                type="button"
                class="category-btn"
                data-category-id="{{ $category->id }}"
            >

                <i class="bi {{ $icon }}"></i>

                <span>
                    {{ $category->name }}
                </span>

            </button>

        @endforeach

    </div>

</aside>