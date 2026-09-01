<script>
        let adjustmentItemIndex =
            parseInt(
                $('#adjustmentItemIndex').val(),
                10
            ) || 1;


        const medicines = @json($medicines);

        /*
        |--------------------------------------------------------------------------
        | Medicine Options
        |--------------------------------------------------------------------------
        */

        function medicineOptions(selected = '') {

            let html = '<option value="">Select Medicine</option>';


            medicines.forEach(function(medicine) {

                html += `
                <option
                    value="${medicine.id}"
                    ${selected == medicine.id ? 'selected' : ''}
                >
                    ${medicine.name}
                </option>
            `;

            });


            return html;
        }


        /*
        |--------------------------------------------------------------------------
        | Add Row
        |--------------------------------------------------------------------------
        */

        $('#addAdjustmentItem').on('click',
            function() {
                const index = adjustmentItemIndex++;

                const row = `
                    <tr>

                        <td>

                            <select
                                name="items[${index}][medicine_id]"
                                class="form-select medicine-select"
                                required
                            >

                                ${medicineOptions()}

                            </select>

                        </td>


                        <td>

                            <select
                                name="items[${index}][purchase_item_id]"
                                class="form-select batch-select"
                            >

                                <option value="">
                                    Select Batch
                                </option>

                            </select>

                        </td>


                        <td>

                            <input
                                type="date"
                                name="items[${index}][expiry_date]"
                                class="form-control expiry-date"
                                readonly
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                class="form-control available-quantity"
                                readonly
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[${index}][quantity]"
                                class="form-control quantity"
                                min="1"
                                value="1"
                                required
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[${index}][purchase_price]"
                                class="form-control purchase-price"
                                step="0.01"
                                min="0"
                                readonly
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                name="items[${index}][selling_price]"
                                class="form-control selling-price"
                                step="0.01"
                                min="0"
                                readonly
                            >

                        </td>


                        <td>

                            <button
                                type="button"
                                class="btn btn-danger btn-sm remove-adjustment-item"
                            >

                                <i class="fas fa-trash"></i>

                            </button>

                        </td>

                    </tr>
                `;


                $('#adjustmentItemsBody').append(row);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Remove Row
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.remove-adjustment-item',
            function() {

                const rows =
                    $('#adjustmentItemsBody tr');


                if (rows.length <= 1) {

                    const row =
                        $(this).closest('tr');


                    row.find(
                            'input, select'
                        ).not('.medicine-select')
                        .val('');


                    row.find('.medicine-select')
                        .val('');

                    return;
                }


                $(this)
                    .closest('tr')
                    .remove();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Medicine Change
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.medicine-select',
            function() {

                const $medicine = $(this);

                const $row = $medicine.closest('tr');

                const medicineId = $medicine.val();

                const $batch = $row.find('.batch-select');

                const selectedBatch =
                    $batch.data('selected-batch') ||
                    $batch.attr('data-selected-batch') ||
                    '';


                /*
                |--------------------------------------------------------------------------
                | Clear Current Batch Data
                |--------------------------------------------------------------------------
                */

                $batch
                    .empty()
                    .append(`
                <option value="">
                    Loading batches...
                </option>
            `)
                    .prop('disabled', true);


                $row.find('.expiry-date').val('');
                $row.find('.available-quantity').val('');
                $row.find('.purchase-price').val('');
                $row.find('.selling-price').val('');


                if (!medicineId) {

                    $batch
                        .empty()
                        .append(`
                    <option value="">
                        Select Batch
                    </option>
                `);

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Increase
                |--------------------------------------------------------------------------
                |
                | No existing batch is selected.
                |
                */

                if ($('#type').val() === 'increase') {

                    $batch
                        .empty()
                        .append(`
                    <option value="">
                        New Adjustment Batch
                    </option>
                `)
                        .prop('disabled', true);

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Decrease
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url: "{{ url('stock-adjustments/medicine') }}" +
                        '/' +
                        medicineId +
                        '/batches',

                    type: 'GET',

                    success: function(response) {

                        $batch.empty();


                        if (
                            !response.success ||
                            !Array.isArray(response.batches) ||
                            response.batches.length === 0
                        ) {

                            $batch
                                .append(`
                            <option value="">
                                No available batches
                            </option>
                        `)
                                .prop('disabled', true);

                            return;
                        }


                        $batch.append(`
                    <option value="">
                        Select Batch
                    </option>
                `);


                        response.batches.forEach(
                            function(batch) {

                                const option =
                                    $('<option>', {

                                        value: batch.id,

                                        text: (
                                                batch.batch_number ||
                                                'No Batch'
                                            ) +
                                            ' — Available: ' +
                                            batch.available_quantity

                                    });


                                option.attr(
                                    'data-batch',
                                    JSON.stringify(batch)
                                );


                                $batch.append(option);

                            }
                        );


                        $batch.prop(
                            'disabled',
                            false
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Restore Existing Batch In Edit Mode
                        |--------------------------------------------------------------------------
                        */

                        if (selectedBatch) {

                            $batch
                                .val(selectedBatch)
                                .trigger('change');

                        }

                    },

                    error: function(xhr) {

                        $batch
                            .empty()
                            .append(`
                        <option value="">
                            Unable to load batches
                        </option>
                    `)
                            .prop('disabled', true);


                        toastr.error(
                            xhr.responseJSON?.message ||
                            'Unable to load medicine batches.'
                        );

                    }

                });

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Batch Change
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.batch-select',
            function() {

                const $batch = $(this);

                const $row =
                    $batch.closest('tr');

                const raw =
                    $batch
                    .find(':selected')
                    .attr('data-batch');


                if (!raw) {

                    $row.find('.expiry-date').val('');

                    $row.find('.available-quantity').val('');

                    $row.find('.purchase-price').val('');

                    $row.find('.selling-price').val('');

                    return;
                }


                const batch =
                    JSON.parse(raw);


                $row.find('.expiry-date').val(
                    batch.expiry_date || ''
                );


                $row.find('.available-quantity').val(
                    batch.available_quantity
                );


                $row.find('.purchase-price').val(
                    parseFloat(
                        batch.purchase_price || 0
                    ).toFixed(2)
                );


                $row.find('.selling-price').val(
                    parseFloat(
                        batch.selling_price || 0
                    ).toFixed(2)
                );


                $row.find('.quantity')
                    .attr(
                        'max',
                        batch.available_quantity
                    );


                /*
                |--------------------------------------------------------------------------
                | Keep Current Quantity Valid
                |--------------------------------------------------------------------------
                */

                let quantity =
                    parseInt(
                        $row.find('.quantity').val(),
                        10
                    ) || 0;


                const max =
                    parseInt(
                        batch.available_quantity,
                        10
                    ) || 0;


                if (quantity <= 0) {
                    quantity = 1;
                }


                if (quantity > max) {
                    quantity = max;
                }


                $row.find('.quantity').val(
                    quantity
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Type Change
        |--------------------------------------------------------------------------
        */

        $('#type').on(
            'change',
            function() {

                /*
                |--------------------------------------------------------------------------
                | Re-trigger medicine changes
                |--------------------------------------------------------------------------
                */

                $('.medicine-select').each(
                    function() {

                        if ($(this).val()) {

                            $(this)
                                .trigger('change');

                        }

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Quantity Protection
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.quantity',
            function() {

                const $input =
                    $(this);

                const max =
                    parseInt(
                        $input.attr('max'),
                        10
                    );


                if (isNaN(max)) {
                    return;
                }


                let value =
                    parseInt(
                        $input.val(),
                        10
                    ) || 0;


                value =
                    Math.max(
                        1,
                        Math.min(
                            value,
                            max
                        )
                    );


                $input.val(value);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Type
        |--------------------------------------------------------------------------
        */

        $('#type').on(
            'change',
            function() {

                $('.medicine-select').each(
                    function() {

                        if ($(this).val()) {

                            $(this).trigger('change');

                        }

                    }
                );

            }
        );
    </script>