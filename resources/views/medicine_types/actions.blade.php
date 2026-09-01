<a href="{{ route('medicine-types.edit',$row) }}"
    class="btn btn-warning btn-sm">

    <i class="fas fa-edit"></i>

</a>

<form
    action="{{ route('medicine-types.destroy',$row) }}"
    method="POST"
    class="d-inline delete-form"
>

    @csrf

    @method('DELETE')

    <button
        class="btn btn-danger btn-sm"
        type="submit"
    >

        <i class="fas fa-trash"></i>

    </button>

</form>