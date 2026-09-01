<script>

    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    let purchaseItems = [];

    let rowIndex =
        parseInt($('#rowIndex').val(), 10) || 0;


    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    */

    const isPurchaseLocked =
        @json($lockedPurchase ?? false);


    /*
    |--------------------------------------------------------------------------
    | Laravel Routes
    |--------------------------------------------------------------------------
    */

    const purchaseItemsUrl =
        "{{ route(
            'purchase-returns.purchase-items',
            ['purchase' => '__PURCHASE__']
        ) }}";


    const supplierPurchasesUrl =
        "{{ route(
            'purchase-returns.supplier-purchases',
            ['supplier' => '__SUPPLIER__']
        ) }}";


    /*
    |--------------------------------------------------------------------------
    | Route Helpers
    |--------------------------------------------------------------------------
    */

    function getPurchaseItemsUrl(purchaseId)
    {
        return purchaseItemsUrl.replace(
            '__PURCHASE__',
            purchaseId
        );
    }


    function getSupplierPurchasesUrl(supplierId)
    {
        return supplierPurchasesUrl.replace(
            '__SUPPLIER__',
            supplierId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Grand Total
    |--------------------------------------------------------------------------
    */

    function calculateGrandTotal()
    {
        let subtotal = 0;


        $('.total').each(function () {

            subtotal +=
                parseFloat($(this).val()) || 0;

        });


        $('#subtotal').val(
            subtotal.toFixed(2)
        );


        const discount =
            parseFloat($('#discount').val()) || 0;


        const tax =
            parseFloat($('#tax').val()) || 0;


        let grandTotal = subtotal;


        if (
            $('#discount_type').val()
            === 'Percentage'
        ) {

            grandTotal -=
                subtotal * discount / 100;

        } else {

            grandTotal -= discount;

        }


        if (
            $('#tax_type').val()
            === 'Percentage'
        ) {

            grandTotal +=
                subtotal * tax / 100;

        } else {

            grandTotal += tax;

        }


        grandTotal = Math.max(
            grandTotal,
            0
        );


        $('#grand_total').val(
            grandTotal.toFixed(2)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Row
    |--------------------------------------------------------------------------
    */

    function calculateRow($row)
    {
        const quantity =
            parseFloat(
                $row.find('.quantity').val()
            ) || 0;


        const price =
            parseFloat(
                $row.find('.purchase_price').val()
            ) || 0;


        $row.find('.total').val(
            (
                quantity * price
            ).toFixed(2)
        );


        calculateGrandTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | Clear Row
    |--------------------------------------------------------------------------
    */

    function clearReturnRow($row)
    {
        $row.find('.purchase_item_id').val('');

        $row.find('.medicine_id').val('');

        $row.find('.batch_number').val('');

        $row.find('.expiry_date').val('');

        $row.find('.purchased').val('');

        $row.find('.returned').val('');

        $row.find('.remaining').val('');

        $row.find('.purchase_price').val('');

        $row.find('.quantity')
            .val(0)
            .removeAttr('max');

        $row.find('.remaining-help')
            .text('Select medicine');

        $row.find('.total')
            .val('0.00');

        calculateGrandTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | Add Row
    |--------------------------------------------------------------------------
    */

    $('#addRow').on('click', function () {

        addRow();

    });


    function addRow()
    {
        const index = rowIndex;


        const row = `
            <tr>

                <td>

                    <input
                        type="hidden"
                        name="items[${index}][purchase_item_id]"
                        class="purchase_item_id"
                    >

                    <input
                        type="hidden"
                        name="items[${index}][medicine_id]"
                        class="medicine_id"
                    >

                    <select
                        class="form-select medicine-select"
                    >
                        <option value="">
                            Select Medicine
                        </option>
                    </select>

                </td>


                <td>

                    <input
                        type="text"
                        name="items[${index}][batch_number]"
                        class="form-control batch_number"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="date"
                        name="items[${index}][expiry_date]"
                        class="form-control expiry_date"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="form-control purchased"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="form-control returned"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="form-control remaining"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="items[${index}][quantity]"
                        class="form-control quantity"
                        min="0"
                        value="0"
                    >

                    <small class="text-muted remaining-help">
                        Select medicine
                    </small>

                </td>


                <td>

                    <input
                        type="number"
                        step="0.01"
                        name="items[${index}][purchase_price]"
                        class="form-control purchase_price"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="number"
                        step="0.01"
                        name="items[${index}][total]"
                        class="form-control total"
                        readonly
                    >

                </td>


                <td>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm removeRow"
                    >
                        <i class="fas fa-trash"></i>
                    </button>

                </td>

            </tr>
        `;


        $('#itemsTable tbody').append(row);

        rowIndex++;

        loadMedicineOptions();
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Row
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.removeRow',
        function () {

            const $row =
                $(this).closest('tr');

            const rowCount =
                $('#itemsTable tbody tr').length;


            if (rowCount <= 1) {

                clearReturnRow($row);

                return;
            }


            $row.remove();

            calculateGrandTotal();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Supplier → Purchases
    |--------------------------------------------------------------------------
    |
    | Disabled in shortcut mode because Purchase controls Supplier.
    |
    */

    $('#supplier_id').on(
        'change',
        function () {

            if (isPurchaseLocked) {
                return;
            }


            const supplierId =
                $(this).val();

            const $purchase =
                $('#purchase_id');


            purchaseItems = [];

            $('#itemsTable tbody')
                .empty();

            rowIndex = 0;


            if (!supplierId) {

                $purchase
                    .empty()
                    .append(
                        $('<option>', {
                            value: '',
                            text: 'Select Purchase'
                        })
                    )
                    .prop(
                        'disabled',
                        false
                    );

                calculateGrandTotal();

                return;
            }


            $purchase
                .empty()
                .append(
                    $('<option>', {
                        value: '',
                        text: 'Loading purchases...'
                    })
                )
                .prop(
                    'disabled',
                    true
                );


            $.ajax({

                url:
                    getSupplierPurchasesUrl(
                        supplierId
                    ),

                type: 'GET',

                success: function (response) {

                    $purchase.empty();


                    if (
                        !response.success ||
                        !Array.isArray(
                            response.purchases
                        )
                    ) {

                        $purchase
                            .append(
                                $('<option>', {
                                    value: '',
                                    text: 'No purchases found'
                                })
                            )
                            .prop(
                                'disabled',
                                true
                            );

                        return;
                    }


                    $purchase.append(
                        $('<option>', {
                            value: '',
                            text: 'Select Purchase'
                        })
                    );


                    response.purchases.forEach(
                        function (purchase) {
                            const date = purchase.purchase_date
                            ? new Date(purchase.purchase_date).toLocaleDateString(
                                'en-GB',
                                {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                }
                            )
                            : '-';
                            $purchase.append(
                                $('<option>', {
                                    value: purchase.id,

                                    text:
                                        purchase.purchase_number +
                                        ' - ' +
                                        date
                                })
                            );

                        }
                    );


                    $purchase.prop(
                        'disabled',
                        false
                    );
                },

                error: function (xhr) {

                    toastr.error(
                        xhr.responseJSON?.message ||
                        'Unable to load purchases.'
                    );

                    $purchase.prop(
                        'disabled',
                        false
                    );
                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Purchase → Supplier + Items
    |--------------------------------------------------------------------------
    */

    $('#purchase_id').on(
        'change',
        function () {

            const purchaseId =
                $(this).val();


            const $supplier =
                $('#supplier_id');


            purchaseItems = [];

            $('#itemsTable tbody')
                .empty();

            rowIndex = 0;


            if (!purchaseId) {

                if (!isPurchaseLocked) {

                    $supplier
                        .val('')
                        .prop(
                            'disabled',
                            false
                        );

                }

                calculateGrandTotal();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Lock Supplier
            |--------------------------------------------------------------------------
            */

            $supplier.prop(
                'disabled',
                true
            );


            $.ajax({

                url:
                    getPurchaseItemsUrl(
                        purchaseId
                    ),

                type: 'GET',

                success: function (response) {

                    if (!response.success) {

                        toastr.error(
                            response.message ||
                            'Unable to load purchase.'
                        );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Supplier
                    |--------------------------------------------------------------------------
                    */

                    $supplier
                        .val(
                            response.purchase.supplier_id
                        )
                        .prop(
                            'disabled',
                            true
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | Keep the hidden supplier value synchronized.
                    |--------------------------------------------------------------------------
                    */

                    $('#supplier_id_hidden')
                        .val(
                            response.purchase.supplier_id
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Purchase Items
                    |--------------------------------------------------------------------------
                    */

                    purchaseItems =
                        response.items || [];


                    if (
                        purchaseItems.length === 0
                    ) {

                        $('#itemsTable tbody')
                            .html(`
                                <tr>
                                    <td
                                        colspan="10"
                                        class="text-center text-muted py-4"
                                    >
                                        No returnable medicines found.
                                    </td>
                                </tr>
                            `);

                        calculateGrandTotal();

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Add Medicine Row
                    |--------------------------------------------------------------------------
                    */

                    addRow();

                },

                error: function (xhr) {

                    purchaseItems = [];

                    $('#itemsTable tbody')
                        .empty();

                    toastr.error(
                        xhr.responseJSON?.message ||
                        'Unable to load purchase items.'
                    );

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Load Medicine Options
    |--------------------------------------------------------------------------
    */

    function loadMedicineOptions()
    {
        const $select =
            $('#itemsTable tbody tr:last .medicine-select');


        if (!$select.length) {
            return;
        }


        $select
            .empty()
            .append(
                $('<option>', {
                    value: '',
                    text: 'Select Medicine'
                })
            );


        let returnableCount = 0;


        purchaseItems.forEach(
            function (item) {

                const remaining =
                    parseInt(
                        item.remaining_quantity,
                        10
                    ) || 0;


                if (remaining <= 0) {
                    return;
                }


                returnableCount++;


                const option =
                    $('<option>', {

                        value:
                            item.purchase_item_id,

                        text:
                            item.medicine.name +
                            ' (' +
                            item.batch_number +
                            ')'

                    });


                option.attr(
                    'data-item',
                    JSON.stringify(item)
                );


                $select.append(option);

            }
        );


        if (returnableCount === 0) {

            $select
                .prop(
                    'disabled',
                    true
                )
                .append(
                    $('<option>', {
                        value: '',
                        text: 'No returnable medicines'
                    })
                );

        } else {

            $select.prop(
                'disabled',
                false
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Medicine Selected
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.medicine-select',
        function () {

            const $select =
                $(this);

            const $row =
                $select.closest('tr');


            const rawData =
                $select
                    .find(':selected')
                    .attr('data-item');


            if (!rawData) {

                clearReturnRow(
                    $row
                );

                return;
            }


            let item;


            try {

                item =
                    JSON.parse(
                        rawData
                    );

            } catch (error) {

                clearReturnRow(
                    $row
                );

                toastr.error(
                    'Unable to load medicine information.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Fill Item Information
            |--------------------------------------------------------------------------
            */

            $row
                .find('.purchase_item_id')
                .val(
                    item.purchase_item_id
                );


            $row
                .find('.medicine_id')
                .val(
                    item.medicine.id
                );


            $row
                .find('.batch_number')
                .val(
                    item.batch_number || ''
                );


            $row
                .find('.expiry_date')
                .val(
                    item.expiry_date || ''
                );


            $row
                .find('.purchased')
                .val(
                    item.quantity || 0
                );


            $row
                .find('.returned')
                .val(
                    item.returned_quantity || 0
                );


            $row
                .find('.remaining')
                .val(
                    item.remaining_quantity || 0
                );


            $row
                .find('.purchase_price')
                .val(
                    parseFloat(
                        item.purchase_price || 0
                    ).toFixed(2)
                );


            /*
            |--------------------------------------------------------------------------
            | Return Quantity
            |--------------------------------------------------------------------------
            */

            const remaining =
                parseInt(
                    item.remaining_quantity,
                    10
                ) || 0;


            const $quantity =
                $row.find('.quantity');


            $quantity
                .attr(
                    'max',
                    remaining
                )
                .val(
                    remaining > 0
                        ? 1
                        : 0
                );


            $row
                .find('.remaining-help')
                .text(
                    'Maximum: ' +
                    remaining
                );


            calculateRow(
                $row
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Quantity Validation
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '.quantity',
        function () {

            const $input =
                $(this);


            const max =
                parseInt(
                    $input.attr('max'),
                    10
                ) || 0;


            let value =
                parseInt(
                    $input.val(),
                    10
                ) || 0;


            value = Math.max(
                0,
                Math.min(
                    value,
                    max
                )
            );


            $input.val(
                value
            );


            calculateRow(
                $input.closest('tr')
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Discount / Tax
    |--------------------------------------------------------------------------
    */

    $('#discount, #tax, #discount_type, #tax_type')
        .on(
            'input change',
            function () {

                calculateGrandTotal();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZATION
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {

        const selectedPurchase =
            $('#purchase_id').val();


        /*
        |--------------------------------------------------------------------------
        | Purchase Shortcut Mode
        |--------------------------------------------------------------------------
        |
        | Purchase and supplier come from:
        |
        | /purchase-returns/create?purchase=123
        |
        | They must not be changed.
        |
        */

        if (
            isPurchaseLocked &&
            selectedPurchase
        ) {

            $('#purchase_id')
                .prop(
                    'disabled',
                    true
                );


            $('#supplier_id')
                .prop(
                    'disabled',
                    true
                );


            /*
            |--------------------------------------------------------------------------
            | Load purchase and returnable medicine options.
            |--------------------------------------------------------------------------
            */

            $('#purchase_id')
                .trigger('change');

        }


        /*
        |--------------------------------------------------------------------------
        | Normal Create Mode
        |--------------------------------------------------------------------------
        */

        if (
            !isPurchaseLocked &&
            selectedPurchase
        ) {

            $('#purchase_id')
                .trigger('change');

        }


        calculateGrandTotal();

    });

</script>
