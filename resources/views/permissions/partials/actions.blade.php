<div class="dropdown">

    <button
        class="btn btn-primary btn-sm dropdown-toggle"
        data-bs-toggle="dropdown"
    >

        Actions

    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        {{-- view --}}
        <li>

            <a
                href="{{ route('permissions.show',$row) }}"
                class="dropdown-item"
            >

                <i class="fas fa-eye me-2 text-info"></i>

                View

            </a>

        </li>

        {{-- edit --}}
        <li>

            <a
                href="{{ route('permissions.edit',$row) }}"
                class="dropdown-item"
            >

                <i class="fas fa-edit me-2 text-warning"></i>

                Edit

            </a>

        </li>

        <li>

            <hr class="dropdown-divider">

        </li>

        {{-- delete --}}
        <li>

            <form
                action="{{ route('permissions.destroy',$row) }}"
                method="POST"
                class="delete-form"
            >

                @csrf

                @method('DELETE')

                <button
                    class="dropdown-item text-danger"
                >

                    <i class="fas fa-trash me-2"></i>

                    Delete

                </button>

            </form>

        </li>

    </ul>

</div>