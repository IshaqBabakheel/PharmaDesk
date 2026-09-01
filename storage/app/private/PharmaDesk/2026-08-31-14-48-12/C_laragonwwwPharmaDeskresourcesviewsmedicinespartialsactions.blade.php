<div class="dropdown">

    <button
        class="btn btn-sm btn-primary dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >

        <i class="fas fa-ellipsis-v me-1"></i>

        Actions

    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow">

        {{-- View --}}

        <li>

            <a
                href="{{ route('medicines.show', $row) }}"
                class="dropdown-item"
            >

                <i class="fas fa-eye text-info me-2"></i>

                View Details

            </a>

        </li>



        {{-- Edit --}}

        <li>

            <a
                href="{{ route('medicines.edit', $row) }}"
                class="dropdown-item"
            >

                <i class="fas fa-edit text-warning me-2"></i>

                Edit

            </a>

        </li>



        <li><hr class="dropdown-divider"></li>



        {{-- Stock History --}}

        <li>

            <a
                href="#"
                class="dropdown-item"
            >

                <i class="fas fa-clock-rotate-left text-primary me-2"></i>

                Stock History

            </a>

        </li>



        {{-- Purchase History --}}

        <li>

            <a
                href="#"
                class="dropdown-item"
            >

                <i class="fas fa-cart-shopping text-success me-2"></i>

                Purchase History

            </a>

        </li>



        {{-- Sales History --}}

        <li>

            <a
                href="#"
                class="dropdown-item"
            >

                <i class="fas fa-cash-register text-secondary me-2"></i>

                Sales History

            </a>

        </li>



        <li><hr class="dropdown-divider"></li>



        {{-- Print Barcode --}}

        <li>

            <a
                href="#"
                class="dropdown-item"
            >

                <i class="fas fa-barcode text-dark me-2"></i>

                Print Barcode

            </a>

        </li>



        {{-- Duplicate --}}

        <li>

            <a
                href="#"
                class="dropdown-item"
            >

                <i class="fas fa-copy text-primary me-2"></i>

                Duplicate

            </a>

        </li>



        <li><hr class="dropdown-divider"></li>



        {{-- Delete --}}

        <li>

            <form
                action="{{ route('medicines.destroy', $row) }}"
                method="POST"
                class="delete-form"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="dropdown-item text-danger"
                >

                    <i class="fas fa-trash me-2"></i>

                    Delete

                </button>

            </form>

        </li>

    </ul>

</div>