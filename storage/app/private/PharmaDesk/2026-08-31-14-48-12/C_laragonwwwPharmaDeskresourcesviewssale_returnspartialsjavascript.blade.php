{{-- <script>
    $(document).ready(function() {
        const sales = @json($sales);
        const oldItems = @json(old('items', []));
        const lockedSale = @json($lockedSale ?? false);
        let selectedSale = null;

        // ✅ If locked sale, load items immediately
        if (lockedSale) {
            const saleId = $('#sale_id').val();
            if (saleId) {
                selectedSale = sales.find(sale => parseInt(sale.id) === parseInt(saleId));
                loadSaleItems();
            }
            return;
        }

        // Normal mode - change handler
        $('#sale_id').on('change', function() {
            const saleId = parseInt($(this).val());
            selectedSale = sales.find(sale => parseInt(sale.id) === saleId);
            loadSaleItems();
        });

        function loadSaleItems() {
            const tbody = $('#returnItemsBody');
            tbody.empty();

            if (!selectedSale) {
                $('#itemsEmpty').removeClass('d-none');
                $('#itemsContainer').addClass('d-none');
                $('#customer_name').val('');
                $('#customer_id').val('');
                $('#sale_total').val('');
                updateTotals();
                return;
            }

            $('#itemsEmpty').addClass('d-none');
            $('#itemsContainer').removeClass('d-none');

            $('#customer_name').val(selectedSale.customer?.name || 'Walk-in Customer');
            $('#customer_id').val(selectedSale.customer_id || '');
            $('#sale_total').val(parseFloat(selectedSale.grand_total || 0).toFixed(2));

            if (!selectedSale.items || selectedSale.items.length === 0) {
                tbody.html(`
                <tr>
                    <td colspan="11" class="text-center text-muted">
                        No sale items found.
                    </td>
                </tr>
            `);
                return;
            }

            selectedSale.items.forEach(function(item, index) {
                const oldItem = oldItems.find(old => parseInt(old.sale_item_id) === parseInt(item
                    .id)) || {};

                const returnedQuantity = parseInt(item.returned_quantity || 0);
                const returnedFreeQuantity = parseInt(item.returned_free_quantity || 0);

                const remainingQuantity = Math.max(0, parseInt(item.quantity || 0) - returnedQuantity);
                const remainingFreeQuantity = Math.max(0, parseInt(item.free_quantity || 0) -
                    returnedFreeQuantity);

                const returnQuantity = parseInt(oldItem.quantity || 0);
                const returnFreeQuantity = parseInt(oldItem.free_quantity || 0);

                const sellingPrice = parseFloat(item.selling_price || 0);

                const row = `
                <tr data-sale-item-id="${item.id}"
                    data-max-quantity="${remainingQuantity}"
                    data-max-free-quantity="${remainingFreeQuantity}">
                    <td>
                        <strong>${escapeHtml(item.medicine?.name || '-')}</strong>
                        <input type="hidden" name="items[${index}][sale_item_id]" value="${item.id}">
                    </td>
                    <td>${escapeHtml(item.batch_number || '-')}</td>
                    <td>${item.expiry_date ? formatDate(item.expiry_date) : '-'}</td>
                    <td><strong>${item.quantity}</strong></td>
                    <td><strong class="${returnedQuantity > 0 ? 'text-danger' : ''}">${returnedQuantity}</strong></td>
                    <td>
                        <input type="number" name="items[${index}][quantity]"
                            class="form-control return-quantity"
                            min="0" max="${remainingQuantity}"
                            value="${returnQuantity}"
                            data-price="${sellingPrice}"
                            data-max="${remainingQuantity}"
                            ${remainingQuantity === 0 ? 'readonly' : ''}>
                        <small class="text-muted">Max: <strong>${remainingQuantity}</strong></small>
                    </td>
                    <td>${item.free_quantity || 0}</td>
                    <td><strong class="${returnedFreeQuantity > 0 ? 'text-danger' : ''}">${returnedFreeQuantity}</strong></td>
                    <td>
                        <input type="number" name="items[${index}][free_quantity]"
                            class="form-control return-free-quantity"
                            min="0" max="${remainingFreeQuantity}"
                            value="${returnFreeQuantity}"
                            data-max="${remainingFreeQuantity}"
                            ${remainingFreeQuantity === 0 ? 'readonly' : ''}>
                        <small class="text-muted">Max: <strong>${remainingFreeQuantity}</strong></small>
                    </td>
                    <td>${sellingPrice.toFixed(2)}</td>
                    <td class="item-total">${(returnQuantity * sellingPrice).toFixed(2)}</td>
                </tr>
            `;

                tbody.append(row);
            });

            updateTotals();
        }

        $(document).on(
            'input',
            '.return-quantity, .return-free-quantity',
            function() {

                const input = $(this);

                const max =
                    parseInt(input.attr('max')) || 0;

                let value =
                    parseInt(input.val()) || 0;


                value = Math.max(
                    0,
                    Math.min(value, max)
                );


                input.val(value);

                updateTotals();

            }
        );


        $('#discount, #tax, #other_charges')
            .on('input', updateTotals);


        function updateTotals() {

            let subtotal = 0;


            $('#returnItemsBody tr').each(function() {

                const row = $(this);

                const quantity =
                    parseInt(
                        row.find('.return-quantity').val()
                    ) || 0;


                const price =
                    parseFloat(
                        row.find('.return-quantity')
                        .data('price')
                    ) || 0;


                const total =
                    quantity * price;


                row.find('.item-total')
                    .text(total.toFixed(2));


                subtotal += total;

            });


            const discount =
                parseFloat($('#discount').val()) || 0;

            const tax =
                parseFloat($('#tax').val()) || 0;

            const otherCharges =
                parseFloat(
                    $('#other_charges').val()
                ) || 0;


            const grandTotal =
                subtotal -
                discount +
                tax +
                otherCharges;


            $('#subtotalDisplay')
                .text(subtotal.toFixed(2));


            $('#grandTotalDisplay')
                .text(
                    Math.max(0, grandTotal)
                    .toFixed(2)
                );

        }


        function formatDate(dateString) {

            const date = new Date(dateString);

            if (isNaN(date)) {
                return '-';
            }


            return date.toLocaleDateString(
                'en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }
            );

        }


        function escapeHtml(value) {

            return $('<div>')
                .text(value)
                .html();

        }


        $(document).ready(function() {

            const selectedId =
                parseInt($('#sale_id').val());


            if (selectedId) {

                selectedSale =
                    sales.find(
                        sale =>
                        parseInt(sale.id) ===
                        selectedId
                    );

                loadSaleItems();

            }

        });


        $('#saleReturnForm').on(
            'submit',
            function(e) {

                let hasQuantity = false;


                $('.return-quantity, .return-free-quantity')
                    .each(function() {

                        if (
                            parseInt($(this).val()) > 0
                        ) {

                            hasQuantity = true;

                        }

                    });


                if (!hasQuantity) {

                    e.preventDefault();


                    Swal.fire({

                        icon: 'warning',

                        title: 'No Items Selected',

                        text: 'Please enter a return quantity for at least one item.'

                    });

                    return false;

                }

            }
        );
    });









    // const sales = @json($sales);

    //         const oldItems = @json(old('items', []));

    //         let selectedSale = null;


    //         $('#sale_id').on('change', function() {

    //             const saleId = parseInt($(this).val());

    //             selectedSale = sales.find(
    //                 sale => parseInt(sale.id) === saleId
    //             );

    //             loadSaleItems();

    //         });


    //         function loadSaleItems() {

    //             const tbody = $('#returnItemsBody');

    //             tbody.empty();


    //             if (!selectedSale) {

    //                 $('#itemsEmpty').removeClass('d-none');

    //                 $('#itemsContainer').addClass('d-none');

    //                 $('#customer_name').val('');

    //                 $('#customer_id').val('');

    //                 $('#sale_total').val('');

    //                 updateTotals();

    //                 return;

    //             }


    //             $('#itemsEmpty').addClass('d-none');

    //             $('#itemsContainer').removeClass('d-none');


    //             $('#customer_name').val(
    //                 selectedSale.customer?.name || 'Walk-in Customer'
    //             );


    //             $('#customer_id').val(
    //                 selectedSale.customer_id || ''
    //             );


    //             $('#sale_total').val(
    //                 parseFloat(selectedSale.grand_total || 0).toFixed(2)
    //             );


    //             if (
    //                 !selectedSale.items ||
    //                 selectedSale.items.length === 0
    //             ) {

    //                 tbody.html(`
    //                 <tr>
    //                     <td colspan="11"
    //                         class="text-center text-muted">
    //                         No sale items found.
    //                     </td>
    //                 </tr>
    //             `);

    //                 return;

    //             }


    //             selectedSale.items.forEach(function(item, index) {

    //                 const oldItem =
    //                     oldItems.find(
    //                         old =>
    //                         parseInt(old.sale_item_id) ===
    //                         parseInt(item.id)
    //                     ) || {};


    //                 const returnedQuantity =
    //                     parseInt(item.returned_quantity || 0);


    //                 const returnedFreeQuantity =
    //                     parseInt(item.returned_free_quantity || 0);


    //                 const remainingQuantity =
    //                     Math.max(
    //                         0,
    //                         parseInt(item.quantity || 0) -
    //                         returnedQuantity
    //                     );


    //                 const remainingFreeQuantity =
    //                     Math.max(
    //                         0,
    //                         parseInt(item.free_quantity || 0) -
    //                         returnedFreeQuantity
    //                     );


    //                 const returnQuantity =
    //                     parseInt(oldItem.quantity || 0);


    //                 const returnFreeQuantity =
    //                     parseInt(
    //                         oldItem.free_quantity || 0
    //                     );


    //                 const sellingPrice =
    //                     parseFloat(
    //                         item.selling_price || 0
    //                     );


    //                 const row = `

    //                 <tr>

    //                     <td>

    //                         <strong>
    //                             ${escapeHtml(
    //                                 item.medicine?.name || '-'
    //                             )}
    //                         </strong>

    //                         <input
    //                             type="hidden"
    //                             name="items[${index}][sale_item_id]"
    //                             value="${item.id}"
    //                         >

    //                     </td>

    //                     <td>
    //                         ${escapeHtml(
    //                             item.batch_number || '-'
    //                         )}
    //                     </td>

    //                     <td>
    //                         ${
    //                             item.expiry_date
    //                                 ? formatDate(
    //                                     item.expiry_date
    //                                 )
    //                                 : '-'
    //                         }
    //                     </td>

    //                     <td>
    //                         ${item.quantity}
    //                     </td>

    //                     <td>
    //                         ${returnedQuantity}
    //                     </td>

    //                     <td>

    //                         <input
    //                             type="number"
    //                             name="items[${index}][quantity]"
    //                             class="form-control return-quantity"
    //                             min="0"
    //                             max="${remainingQuantity}"
    //                             value="${returnQuantity}"
    //                             data-price="${sellingPrice}"
    //                         >

    //                         <small class="text-muted">

    //                             Max:
    //                             ${remainingQuantity}

    //                         </small>

    //                     </td>

    //                     <td>
    //                         ${item.free_quantity || 0}
    //                     </td>

    //                     <td>
    //                         ${returnedFreeQuantity}
    //                     </td>

    //                     <td>

    //                         <input
    //                             type="number"
    //                             name="items[${index}][free_quantity]"
    //                             class="form-control return-free-quantity"
    //                             min="0"
    //                             max="${remainingFreeQuantity}"
    //                             value="${returnFreeQuantity}"
    //                         >

    //                         <small class="text-muted">

    //                             Max:
    //                             ${remainingFreeQuantity}

    //                         </small>

    //                     </td>

    //                     <td>

    //                         ${sellingPrice.toFixed(2)}

    //                         <input
    //                             type="hidden"
    //                             name="items[${index}][selling_price]"
    //                             value="${sellingPrice}"
    //                         >

    //                     </td>

    //                     <td class="item-total">
    //                         0.00
    //                     </td>

    //                 </tr>

    //             `;


    //                 tbody.append(row);

    //             });


    //             updateTotals();

    //         }


    // $(document).on(
    //     'input',
    //     '.return-quantity, .return-free-quantity',
    //     function() {

    //         const input = $(this);

    //         const max =
    //             parseInt(input.attr('max')) || 0;

    //         let value =
    //             parseInt(input.val()) || 0;


    //         value = Math.max(
    //             0,
    //             Math.min(value, max)
    //         );


    //         input.val(value);

    //         updateTotals();

    //     }
    // );


    // $('#discount, #tax, #other_charges')
    //     .on('input', updateTotals);


    // function updateTotals() {

    //     let subtotal = 0;


    //     $('#returnItemsBody tr').each(function() {

    //         const row = $(this);

    //         const quantity =
    //             parseInt(
    //                 row.find('.return-quantity').val()
    //             ) || 0;


    //         const price =
    //             parseFloat(
    //                 row.find('.return-quantity')
    //                 .data('price')
    //             ) || 0;


    //         const total =
    //             quantity * price;


    //         row.find('.item-total')
    //             .text(total.toFixed(2));


    //         subtotal += total;

    //     });


    //     const discount =
    //         parseFloat($('#discount').val()) || 0;

    //     const tax =
    //         parseFloat($('#tax').val()) || 0;

    //     const otherCharges =
    //         parseFloat(
    //             $('#other_charges').val()
    //         ) || 0;


    //     const grandTotal =
    //         subtotal -
    //         discount +
    //         tax +
    //         otherCharges;


    //     $('#subtotalDisplay')
    //         .text(subtotal.toFixed(2));


    //     $('#grandTotalDisplay')
    //         .text(
    //             Math.max(0, grandTotal)
    //             .toFixed(2)
    //         );

    // }


    // function formatDate(dateString) {

    //     const date = new Date(dateString);

    //     if (isNaN(date)) {
    //         return '-';
    //     }


    //     return date.toLocaleDateString(
    //         'en-GB', {
    //             day: '2-digit',
    //             month: 'short',
    //             year: 'numeric'
    //         }
    //     );

    // }


    // function escapeHtml(value) {

    //     return $('<div>')
    //         .text(value)
    //         .html();

    // }


    // $(document).ready(function() {

    //     const selectedId =
    //         parseInt($('#sale_id').val());


    //     if (selectedId) {

    //         selectedSale =
    //             sales.find(
    //                 sale =>
    //                 parseInt(sale.id) ===
    //                 selectedId
    //             );

    //         loadSaleItems();

    //     }

    // });


    // $('#saleReturnForm').on(
    //     'submit',
    //     function(e) {

    //         let hasQuantity = false;


    //         $('.return-quantity, .return-free-quantity')
    //             .each(function() {

    //                 if (
    //                     parseInt($(this).val()) > 0
    //                 ) {

    //                     hasQuantity = true;

    //                 }

    //             });


    //         if (!hasQuantity) {

    //             e.preventDefault();


    //             Swal.fire({

    //                 icon: 'warning',

    //                 title: 'No Items Selected',

    //                 text: 'Please enter a return quantity for at least one item.'

    //             });

    //             return false;

    //         }

    //     }
    // );
</script> --}}

<script>
    $(document).ready(function() {
        const sales = @json($sales);
        const oldItems = @json(old('items', []));
        const lockedSale = @json($lockedSale ?? false);
        const isEdit = @json($isEdit ?? false); // ✅ Pass this from controller
        let selectedSale = null;

        // Helper: Load items from selected sale
        function loadSaleItems() {
            const tbody = $('#returnItemsBody');
            
            // ✅ In edit mode, DO NOT reload rows (keep server-rendered)
            if (isEdit) {
                // Just update totals based on existing inputs
                updateTotals();
                return;
            }

            // Clear existing rows (only in create mode)
            tbody.empty();

            if (!selectedSale) {
                $('#itemsEmpty').removeClass('d-none');
                $('#itemsContainer').addClass('d-none');
                $('#customer_name').val('');
                $('#customer_id').val('');
                $('#sale_total').val('');
                updateTotals();
                return;
            }

            $('#itemsEmpty').addClass('d-none');
            $('#itemsContainer').removeClass('d-none');

            $('#customer_name').val(selectedSale.customer?.name || 'Walk-in Customer');
            $('#customer_id').val(selectedSale.customer_id || '');
            $('#sale_total').val(parseFloat(selectedSale.grand_total || 0).toFixed(2));

            if (!selectedSale.items || selectedSale.items.length === 0) {
                tbody.html(`
                    <tr>
                        <td colspan="11" class="text-center text-muted">
                            No sale items found.
                        </td>
                    </tr>
                `);
                updateTotals();
                return;
            }

            // Build rows from sale items
            selectedSale.items.forEach(function(item, index) {
                const oldItem = oldItems.find(old => parseInt(old.sale_item_id) === parseInt(item.id)) || {};
                
                const returnedQuantity = parseInt(item.returned_quantity || 0);
                const returnedFreeQuantity = parseInt(item.returned_free_quantity || 0);
                
                const remainingQuantity = Math.max(0, parseInt(item.quantity || 0) - returnedQuantity);
                const remainingFreeQuantity = Math.max(0, parseInt(item.free_quantity || 0) - returnedFreeQuantity);
                
                const returnQuantity = parseInt(oldItem.quantity || 0);
                const returnFreeQuantity = parseInt(oldItem.free_quantity || 0);
                
                const sellingPrice = parseFloat(item.selling_price || 0);

                const row = `
                    <tr data-sale-item-id="${item.id}"
                        data-max-quantity="${remainingQuantity}"
                        data-max-free-quantity="${remainingFreeQuantity}">
                        <td>
                            <strong>${escapeHtml(item.medicine?.name || '-')}</strong>
                            <input type="hidden" name="items[${index}][sale_item_id]" value="${item.id}">
                        </td>
                        <td>${escapeHtml(item.batch_number || '-')}</td>
                        <td>${item.expiry_date ? formatDate(item.expiry_date) : '-'}</td>
                        <td><strong>${item.quantity}</strong></td>
                        <td><strong class="${returnedQuantity > 0 ? 'text-danger' : ''}">${returnedQuantity}</strong></td>
                        <td>
                            <input type="number" name="items[${index}][quantity]"
                                class="form-control return-quantity"
                                min="0" max="${remainingQuantity}"
                                value="${returnQuantity}"
                                data-price="${sellingPrice}"
                                data-max="${remainingQuantity}"
                                ${remainingQuantity === 0 ? 'readonly' : ''}>
                            <small class="text-muted">Max: <strong>${remainingQuantity}</strong></small>
                        </td>
                        <td>${item.free_quantity || 0}</td>
                        <td><strong class="${returnedFreeQuantity > 0 ? 'text-danger' : ''}">${returnedFreeQuantity}</strong></td>
                        <td>
                            <input type="number" name="items[${index}][free_quantity]"
                                class="form-control return-free-quantity"
                                min="0" max="${remainingFreeQuantity}"
                                value="${returnFreeQuantity}"
                                data-max="${remainingFreeQuantity}"
                                ${remainingFreeQuantity === 0 ? 'readonly' : ''}>
                            <small class="text-muted">Max: <strong>${remainingFreeQuantity}</strong></small>
                        </td>
                        <td>${sellingPrice.toFixed(2)}</td>
                        <td class="item-total">${(returnQuantity * sellingPrice).toFixed(2)}</td>
                    </tr>
                `;
                tbody.append(row);
            });

            updateTotals();
        }

        // ---------- Event Handlers ----------

        // 1. Locked Sale (Shortcut or Edit)
        if (lockedSale || isEdit) {
            const saleId = $('#sale_id').val();
            if (saleId) {
                selectedSale = sales.find(sale => parseInt(sale.id) === parseInt(saleId));
                // If edit, loadSaleItems will skip reloading rows (due to isEdit check)
                loadSaleItems();
            }
            // In locked mode, do not attach change handler
            if (lockedSale) {
                return; // Prevent normal mode handlers
            }
        }

        // 2. Normal Create Mode - attach change handler
        $('#sale_id').on('change', function() {
            const saleId = parseInt($(this).val());
            selectedSale = sales.find(sale => parseInt(sale.id) === saleId);
            loadSaleItems();
        });

        // 3. Initial load if a sale is preselected (e.g., after validation errors)
        const preselectedId = parseInt($('#sale_id').val());
        if (preselectedId && !lockedSale && !isEdit) {
            selectedSale = sales.find(sale => parseInt(sale.id) === preselectedId);
            loadSaleItems();
        }

        // ---------- Shared Functions ----------

        function updateTotals() {
            let subtotal = 0;
            $('#returnItemsBody tr').each(function() {
                const row = $(this);
                const quantity = parseInt(row.find('.return-quantity').val()) || 0;
                const price = parseFloat(row.find('.return-quantity').data('price')) || 0;
                const total = quantity * price;
                row.find('.item-total').text(total.toFixed(2));
                subtotal += total;
            });

            const discount = parseFloat($('#discount').val()) || 0;
            const tax = parseFloat($('#tax').val()) || 0;
            const otherCharges = parseFloat($('#other_charges').val()) || 0;

            const grandTotal = subtotal - discount + tax + otherCharges;

            $('#subtotalDisplay').text(subtotal.toFixed(2));
            $('#grandTotalDisplay').text(Math.max(0, grandTotal).toFixed(2));
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date)) return '-';
            return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function escapeHtml(value) {
            return $('<div>').text(value).html();
        }

        // ---------- Event Bindings ----------

        $(document).on('input', '.return-quantity, .return-free-quantity', function() {
            const input = $(this);
            const max = parseInt(input.attr('max')) || 0;
            let value = parseInt(input.val()) || 0;
            value = Math.max(0, Math.min(value, max));
            input.val(value);
            updateTotals();
        });

        $('#discount, #tax, #other_charges').on('input', updateTotals);

        // ---------- Form Submit Validation ----------
        $('#saleReturnForm').on('submit', function(e) {
            let hasQuantity = false;
            $('.return-quantity, .return-free-quantity').each(function() {
                if (parseInt($(this).val()) > 0) {
                    hasQuantity = true;
                }
            });

            if (!hasQuantity) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Items Selected',
                    text: 'Please enter a return quantity for at least one item.'
                });
                return false;
            }
        });
    });
</script>
