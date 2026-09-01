<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-end">

            <div class="col-md-4">

                <label
                    for="medicineFilter"
                    class="form-label"
                >
                    Medicine
                </label>

                <select
                    id="medicineFilter"
                    class="form-select"
                >

                    <option value="">
                        All Medicines
                    </option>

                    @foreach ($medicines as $medicine)

                        <option value="{{ $medicine->id }}">
                            {{ $medicine->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-3">

                <label
                    for="categoryFilter"
                    class="form-label"
                >
                    Category
                </label>

                <select
                    id="categoryFilter"
                    class="form-select"
                >

                    <option value="">
                        All Categories
                    </option>

                    @foreach ($categories as $category)

                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-3">

                <label
                    for="stockStatusFilter"
                    class="form-label"
                >
                    Stock Status
                </label>

                <select
                    id="stockStatusFilter"
                    class="form-select"
                >

                    <option value="all">
                        All
                    </option>

                    <option value="in">
                        In Stock
                    </option>

                    <option value="low">
                        Low Stock
                    </option>

                    <option value="out">
                        Out of Stock
                    </option>

                </select>

            </div>


            <div class="col-md-2">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="resetInventoryFilters"
                >
                    <i class="fas fa-rotate-left me-1"></i>
                    Reset
                </button>

            </div>

        </div>

    </div>

</div>