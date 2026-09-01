<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-end">

            {{-- Expiry Filter --}}
            <div class="col-md-4">

                <label for="expiryFilter" class="form-label">
                    Expiry Status
                </label>

                <select id="expiryFilter" class="form-select">

                    <option value="all">
                        All Active Batches
                    </option>

                    <option value="expired">
                        Expired
                    </option>

                    <option value="7">
                        Expiring Within 7 Days
                    </option>

                    <option value="30">
                        Expiring Within 30 Days
                    </option>

                    <option value="60">
                        Expiring Within 60 Days
                    </option>

                    <option value="90">
                        Expiring Within 90 Days
                    </option>

                </select>

            </div>


            {{-- Medicine --}}
            <div class="col-md-4">

                <label for="medicineFilter" class="form-label">
                    Medicine
                </label>

                <input type="text" id="medicineFilter" class="form-control" placeholder="Search medicine...">

            </div>


            {{-- Reset --}}
            <div class="col-md-4">

                <button type="button" class="btn btn-secondary" id="resetExpiryFilters">

                    <i class="fas fa-rotate-left me-1"></i>

                    Reset Filters

                </button>

            </div>

        </div>

    </div>

</div>
