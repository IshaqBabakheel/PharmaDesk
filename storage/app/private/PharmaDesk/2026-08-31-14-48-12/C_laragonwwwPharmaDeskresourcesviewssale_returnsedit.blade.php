@extends('layouts.app')

@section('title', 'Edit Sale Return')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-pen-to-square text-primary"></i>

                Edit Sale Return

            </h3>

            <p class="text-muted mb-0">

                Update sale return
                <strong>{{ $saleReturn->return_number }}</strong>

            </p>

        </div>


        <nav>

            <ol class="breadcrumb justify-content-end mb-2">

                <li class="breadcrumb-item">

                    <a href="{{ route('home') }}">
                        Dashboard
                    </a>

                </li>

                <li class="breadcrumb-item">

                    <a href="{{ route('sale-returns.index') }}">
                        Sale Returns
                    </a>

                </li>

                <li class="breadcrumb-item">

                    <a href="{{ route(
                        'sale-returns.show',
                        $saleReturn
                    ) }}">

                        {{ $saleReturn->return_number }}

                    </a>

                </li>

                <li class="breadcrumb-item active">

                    Edit

                </li>

            </ol>

        </nav>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @include(
        'sale_returns.partials.form',
        [
            'formAction' => route('sale-returns.update', $saleReturn),
            'formMethod' => 'PUT',
            'isEdit' => true,
            'saleReturn' => $saleReturn,
            'sale' => $sale,
            'sales' => $sales,
            'saleItems' => $saleItems,
        ]
    )

</div>

@endsection

@push('scripts')
<script>
    $('#saleReturnForm').on('submit', function (e) {

    let hasError = false;

    $('.return-quantity').each(function () {

        const input = $(this);
        const quantity = parseInt(input.val()) || 0;
        const max = parseInt(input.attr('max')) || 0;

        input.removeClass('is-invalid');
        input.siblings('.frontend-error').remove();

        if (quantity > max) {

            hasError = true;

            input.addClass('is-invalid');

            input.after(`
                <div class="invalid-feedback d-block frontend-error">
                    Only ${max} units are available for return.
                </div>
            `);
        }
    });


    $('.return-free-quantity').each(function () {

        const input = $(this);
        const quantity = parseInt(input.val()) || 0;
        const max = parseInt(input.attr('max')) || 0;

        input.removeClass('is-invalid');
        input.siblings('.frontend-error').remove();

        if (quantity > max) {

            hasError = true;

            input.addClass('is-invalid');

            input.after(`
                <div class="invalid-feedback d-block frontend-error">
                    Only ${max} free units are available for return.
                </div>
            `);
        }
    });


    if (hasError) {

        e.preventDefault();

        const firstError = $('.is-invalid').first();

        if (firstError.length) {

            $('html, body').animate({
                scrollTop: firstError.offset().top - 150
            }, 300);

            firstError.focus();
        }

        return false;
    }

});

$(document).on(
    'input',
    '.return-quantity, .return-free-quantity',
    function () {

        const input = $(this);

        const quantity = parseInt(input.val()) || 0;
        const max = parseInt(input.attr('max')) || 0;

        input.removeClass('is-invalid');
        input.siblings('.frontend-error').remove();

        if (quantity > max) {

            input.addClass('is-invalid');

            input.after(`
                <div class="invalid-feedback d-block frontend-error">
                    Only ${max} units are available for return.
                </div>
            `);
        }
    }
);
</script>

@endpush