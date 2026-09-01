{{-- ========================================================= --}}
{{-- Filters --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fas fa-filter me-2 text-primary"></i>

                Filters

            </h5>

            <button
                class="btn btn-sm btn-outline-secondary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#medicineFilters"
            >

                <i class="fas fa-sliders me-1"></i>

                Show / Hide

            </button>

        </div>

    </div>

    <div
        class="collapse show"
        id="medicineFilters"
    >

        <div class="card-body">

            <form
                id="medicineFilterForm"
                autocomplete="off"
            >

                <div class="row">

                    {{-- Search --}}

                    <div class="col-lg-3 mb-3">

                        <label class="form-label">

                            Search

                        </label>

                        <input
                            type="text"
                            id="search"
                            class="form-control"
                            placeholder="Name, SKU, Barcode..."
                        >

                    </div>



                    {{-- Category --}}

                    <div class="col-lg-2 mb-3">

                        <label class="form-label">

                            Category

                        </label>

                        <select
                            id="filter_category"
                            class="form-select"
                        >

                            <option value="">

                                All Categories

                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                >

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Type --}}

                    <div class="col-lg-2 mb-3">

                        <label class="form-label">

                            Type

                        </label>

                        <select
                            id="filter_type"
                            class="form-select"
                        >

                            <option value="">

                                All Types

                            </option>

                            @foreach($types as $type)

                                <option
                                    value="{{ $type->id }}"
                                >

                                    {{ $type->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Manufacturer --}}

                    <div class="col-lg-2 mb-3">

                        <label class="form-label">

                            Manufacturer

                        </label>

                        <select
                            id="filter_manufacturer"
                            class="form-select"
                        >

                            <option value="">

                                All Manufacturers

                            </option>

                            @foreach($manufacturers as $manufacturer)

                                <option
                                    value="{{ $manufacturer->id }}"
                                >

                                    {{ $manufacturer->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Status --}}

                    <div class="col-lg-1 mb-3">

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            id="filter_status"
                            class="form-select"
                        >

                            <option value="">

                                All

                            </option>

                            <option value="1">

                                Active

                            </option>

                            <option value="0">

                                Inactive

                            </option>

                        </select>

                    </div>



                    {{-- Stock --}}

                    <div class="col-lg-2 mb-3">

                        <label class="form-label">

                            Stock

                        </label>

                        <select
                            id="filter_stock"
                            class="form-select"
                        >

                            <option value="">

                                All

                            </option>

                            <option value="low">

                                Low Stock

                            </option>

                            <option value="out">

                                Out Of Stock

                            </option>

                            <option value="available">

                                Available

                            </option>

                        </select>

                    </div>

                </div>



                <div class="d-flex justify-content-end gap-2">

                    <button
                        type="button"
                        class="btn btn-primary"
                        id="btnFilter"
                    >

                        <i class="fas fa-search me-1"></i>

                        Apply Filters

                    </button>

                    <button
                        type="reset"
                        class="btn btn-outline-success"
                        id="btnReset"
                    >

                        <i class="fas fa-rotate-left me-1"></i>

                        Reset

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>