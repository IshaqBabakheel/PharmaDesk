<script>
    $(function () {

    /* =========================================================
       ROUTES
       ========================================================= */

    const routes = {
        cart: "{{ route('pos.cart') }}",
        customers: "{{ route('pos.customers') }}",
        medicines: "{{ route('pos.medicines') }}",
        search: "{{ route('pos.medicines.search') }}",
        addItem: "{{ route('pos.cart.items.store') }}",
        updateCart: "{{ route('pos.cart.update') }}",
        checkout: "{{ route('pos.checkout') }}",
        hold: "{{ route('pos.hold') }}",
        cancel: "{{ route('pos.cancel') }}",
        updateItem: "{{ route('pos.cart.items.update', ['cartItem' => '__ID__']) }}",
        removeItem: "{{ route('pos.cart.items.destroy', ['cartItem' => '__ID__']) }}"
    };

    const csrfToken = "{{ csrf_token() }}";

    /* =========================================================
       AJAX REQUEST HELPER
       ========================================================= */

    function request(url, options = {}) {
        return fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                ...(options.headers || {})
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                const error = new Error(data.message || 'Request failed');
                error.response = response;
                error.data = data;
                throw error;
            }
            return data;
        });
    }

    /* =========================================================
       STATE
       ========================================================= */

    let cart = null;
    let searchTimer = null;
    let currentCategoryId = '';
    let medicines = [];

    /* =========================================================
       AJAX DEFAULTS
       ========================================================= */

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    });

    /* =========================================================
       HELPERS
       ========================================================= */

    function money(value) {
        return `Rs. ${Number(value || 0).toLocaleString('en-PK', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;
    }

    function escapeHtml(value) {
        return $('<div>')
            .text(value ?? '')
            .html();
    }

    function extractErrors(xhr) {
        const response = xhr?.responseJSON || {};
        if (response.message) {
            if (response.errors) {
                const messages = [];
                $.each(response.errors, function (_, errors) {
                    $.each(errors, function (_, message) {
                        messages.push(message);
                    });
                });
                if (messages.length) {
                    return messages.join('<br>');
                }
            }
            return response.message;
        }
        return 'Something went wrong. Please try again.';
    }

    /* =========================================================
       TOAST
       ========================================================= */

    function showToast(message, type = 'success', duration = 3000) {
        const settings = {
            success: { icon: 'bi-check-circle-fill' },
            danger: { icon: 'bi-x-circle-fill' },
            warning: { icon: 'bi-exclamation-triangle-fill' },
            info: { icon: 'bi-info-circle-fill' }
        };

        const config = settings[type] || settings.success;
        const toastId = 'posToast_' + Date.now();

        const toast = $(`
            <div
                id="${toastId}"
                class="toast pos-toast pos-toast-${type}"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
            >
                <div class="toast-body">
                    <span class="pos-toast-icon">
                        <i class="bi ${config.icon}"></i>
                    </span>
                    <span class="flex-grow-1">
                        ${escapeHtml(message)}
                    </span>
                    <button
                        type="button"
                        class="btn-close btn-sm"
                        data-bs-dismiss="toast"
                        aria-label="Close">
                    </button>
                </div>
            </div>
        `);

        $('#posToastContainer').append(toast);

        const instance = bootstrap.Toast.getOrCreateInstance(
            toast[0],
            { autohide: true, delay: duration }
        );

        toast.on('hidden.bs.toast', function () {
            $(this).remove();
        });

        instance.show();
    }

    function showMessage(message, type = 'danger') {
        showToast(
            $('<div>').html(message).text(),
            type
        );
    }

    function clearMessage() {
        $('#posAlert').empty();
    }

    /* =========================================================
       CART
       ========================================================= */

    function loadCart() {
        return $.ajax({
            url: routes.cart,
            type: 'GET',
            dataType: 'json'
        })
        .done(function (response) {
            cart = response.cart;
            renderCart();
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        });
    }

    function renderCart() {
        const $container = $('#cartItems');

        if (!$container.length || !cart) {
            return;
        }

        $('#cartNumber').text(
            `Cart #${String(cart.id).padStart(6, '0')}`
        );

        if (!cart.items || !cart.items.length) {
            $container.html(`
                <div class="empty-cart" id="emptyCart">
                    <i class="bi bi-cart-x"></i>
                    <div>Your cart is empty</div>
                </div>
            `);
        } else {
            const packs = [
                'pack-blue', 'pack-orange', 'pack-green',
                'pack-purple', 'pack-navy', 'pack-red'
            ];

            $container.html(
                $.map(cart.items, function (item, index) {
                    const total = Number(item.total || 0);
                    const pack = packs[index % packs.length];

                    return `
                        <div class="cart-item" data-cart-item-id="${item.id}">
                            <div class="cart-thumb">
                                <div class="medicine-pack ${pack}"></div>
                            </div>
                            <div>
                                <div class="cart-product-name">
                                    ${escapeHtml(item.medicine?.name)}
                                </div>
                                <div class="quantity">
                                    <button type="button" class="qty-btn cart-decrease" data-id="${item.id}">−</button>
                                    <span class="qty-value">${item.quantity}</span>
                                    <button type="button" class="qty-btn cart-increase" data-id="${item.id}">+</button>
                                </div>
                            </div>
                            <div class="cart-actions">
                                <button type="button" class="remove-item cart-remove" data-id="${item.id}" title="Remove item">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                <div class="cart-price">${money(total)}</div>
                            </div>
                        </div>
                    `;
                }).join('')
            );
        }

        $('#cartSubtotal').text(money(cart.subtotal));
        $('#cartDiscount').text(`- ${money(cart.discount)}`);
        $('#cartTotal').text(money(cart.grand_total));

        const $discount = $('#discountAmount');
        if ($discount.length && document.activeElement !== $discount[0]) {
            $discount.val(Number(cart.discount || 0).toFixed(2));
        }

        updatePayButton();
    }

    function updatePayButton() {
        const $button = $('#payNow');
        if (!$button.length) return;

        const total = Number(cart?.grand_total || 0);
        $button.html(`<i class="bi bi-credit-card me-2"></i>Pay Now ${money(total)}`);
        $button.prop('disabled', !cart?.items?.length);
    }

    /* =========================================================
       MEDICINE PARAMETERS
       ========================================================= */

    function buildMedicineParams(includeSearch = false, search = '') {
        const params = {};
        if (includeSearch && $.trim(search)) {
            params.search = $.trim(search);
        }
        if (currentCategoryId) {
            params.medicine_category_id = currentCategoryId;
        }
        return params;
    }

    /* =========================================================
       LOAD MEDICINES
       ========================================================= */

    function loadMedicines() {
        const $grid = $('#productGrid');

        $grid.empty().html(`
            <div class="loading-grid-overlay">
                <div class="loading-grid-content">
                    <div class="pharmacy-loader">
                        <div class="pharmacy-loader-ring"></div>
                        <div class="pharmacy-loader-ring-inner"></div>
                        <div class="pharmacy-capsule"></div>
                        <div class="pharmacy-cross">+</div>
                    </div>
                </div>
            </div>
        `);

        return $.ajax({
            url: routes.medicines,
            type: 'GET',
            data: buildMedicineParams(),
            dataType: 'json'
        })
        .done(function (response) {
            medicines = response.medicines || [];
            renderProducts();
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
            $grid.html(`
                <div class="loading-grid-overlay error">
                    <div class="loading-grid-content">
                        <div class="error-icon">⚠️</div>
                        <h3 class="loading-grid-title text-danger">Failed to Load</h3>
                        <p class="loading-grid-subtitle">Unable to fetch medicines. Please try again.</p>
                        <button class="btn btn-primary mt-3" onclick="loadMedicines()">
                            <i class="bi bi-arrow-clockwise"></i> Retry
                        </button>
                    </div>
                </div>
            `);
        });
    }

    /* =========================================================
       SEARCH MEDICINES
       ========================================================= */

    function searchMedicines(search = '') {
        const value = $.trim(search);
        if (!value) {
            return loadMedicines();
        }

        return $.ajax({
            url: routes.search,
            type: 'GET',
            data: buildMedicineParams(true, value),
            dataType: 'json'
        })
        .done(function (response) {
            medicines = response.medicines || [];
            renderProducts();
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        });
    }

    /* =========================================================
       RENDER PRODUCTS
       ========================================================= */

    function renderProducts() {
        const $grid = $('#productGrid');

        if (!medicines.length) {
            $grid.html(`
                <div class="text-center text-muted py-5 w-100">
                    <i class="bi bi-search fs-1"></i>
                    <div class="mt-2">No medicines found.</div>
                </div>
            `);
            return;
        }

        const packs = [
            'pack-blue', 'pack-orange', 'pack-green',
            'pack-purple', 'pack-navy', 'pack-red'
        ];

        $grid.html(
            $.map(medicines, function (medicine, index) {
                const stock = Number(medicine.current_stock || 0);
                const outOfStock = stock <= 0;
                const pack = packs[index % packs.length];

                return `
                    <div class="product-card ${outOfStock ? 'out-of-stock' : ''}" data-medicine-id="${medicine.id}">
                        <div class="product-image">
                            <div class="medicine-pack ${pack}"></div>
                        </div>
                        <div class="product-name">${escapeHtml(medicine.name)}</div>
                        <div class="product-meta">${medicine.generic_name ? escapeHtml(medicine.generic_name) : 'Medicine'}</div>
                        <div class="product-footer">
                            <span class="stock">
                                ${outOfStock 
                                    ? `<i class="bi bi-x-circle-fill"></i> Out of Stock`
                                    : `<i class="bi bi-check-circle-fill"></i> ${stock} available`
                                }
                            </span>
                            <p class="price">${money(medicine.selling_price)}</p>
                        </div>
                    </div>
                `;
            }).join('')
        );
    }

    /* =========================================================
       ADD PRODUCT TO CART
       ========================================================= */

    $(document).on('click', '.product-card', function () {
        const $product = $(this);

        if ($product.hasClass('out-of-stock')) {
            showToast('This medicine is out of stock.', 'warning');
            return;
        }

        const medicineId = Number($product.data('medicine-id'));
        if (!medicineId) return;

        if ($product.hasClass('loading')) return;
        $product.addClass('loading');

        $.ajax({
            url: routes.addItem,
            type: 'POST',
            data: {
                medicine_id: medicineId,
                quantity: 1,
                free_quantity: 0
            },
            dataType: 'json'
        })
        .done(function (response) {
            cart = response.cart;
            renderCart();
            clearMessage();

            const medicine = medicines.find(item => Number(item.id) === medicineId);
            showToast(`${medicine?.name || 'Medicine'} added to cart.`, 'success');
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        })
        .always(function () {
            $product.removeClass('loading');
        });
    });

    /* =========================================================
       UPDATE CART QUANTITY
       ========================================================= */

    $(document).on('click', '.cart-increase, .cart-decrease', function () {
        const $button = $(this);
        const id = Number($button.data('id'));

        const item = cart?.items?.find(item => Number(item.id) === id);
        if (!item) return;

        let quantity = Number(item.quantity);
        if ($button.hasClass('cart-increase')) {
            quantity++;
        } else {
            quantity--;
        }

        if (quantity <= 0) {
            removeCartItem(id);
            return;
        }

        const url = routes.updateItem.replace('__ID__', id);
        $button.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'PUT',
            data: {
                quantity: quantity,
                free_quantity: Number(item.free_quantity || 0)
            },
            dataType: 'json'
        })
        .done(function (response) {
            cart = response.cart;
            renderCart();
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        })
        .always(function () {
            $button.prop('disabled', false);
        });
    });

    /* =========================================================
       REMOVE CART ITEM
       ========================================================= */

    function removeCartItem(id) {
        const item = cart?.items?.find(item => Number(item.id) === Number(id));
        const itemName = item?.medicine?.name || 'Medicine';
        const url = routes.removeItem.replace('__ID__', id);

        return $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json'
        })
        .done(function (response) {
            cart = response.cart;
            renderCart();
            showToast(`${itemName} removed from cart.`, 'info');
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        });
    }

    $(document).on('click', '.cart-remove', function () {
        removeCartItem(Number($(this).data('id')));
    });

    /* =========================================================
       SEARCH
       ========================================================= */

    $('#searchInput').on('input', function () {
        clearTimeout(searchTimer);
        const value = $(this).val();
        searchTimer = setTimeout(function () {
            searchMedicines(value);
        }, 250);
    });

    /* =========================================================
       BARCODE / ENTER SEARCH
       ========================================================= */

    $('#searchInput').on('keydown', function (event) {
        if (event.key !== 'Enter') return;
        event.preventDefault();

        const $input = $(this);
        const value = $.trim($input.val());
        if (!value) return;

        $.ajax({
            url: routes.search,
            type: 'GET',
            data: buildMedicineParams(true, value),
            dataType: 'json'
        })
        .done(function (response) {
            const results = response.medicines || [];

            if (results.length === 1) {
                const medicine = results[0];
                if (Number(medicine.current_stock) <= 0) {
                    showToast(`${medicine.name} is out of stock.`, 'warning');
                    return;
                }

                $.ajax({
                    url: routes.addItem,
                    type: 'POST',
                    data: {
                        medicine_id: medicine.id,
                        quantity: 1,
                        free_quantity: 0
                    },
                    dataType: 'json'
                })
                .done(function (addResponse) {
                    cart = addResponse.cart;
                    renderCart();
                    $input.val('');
                    showToast(`${medicine.name} added to cart.`, 'success');
                    loadMedicines();
                })
                .fail(function (xhr) {
                    showToast(extractErrors(xhr), 'danger');
                });
                return;
            }

            medicines = results;
            renderProducts();
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        });
    });

    /* =========================================================
       CATEGORY FILTER
       ========================================================= */

    $(document).on('click', '.category-btn', function () {
        const $button = $(this);
        $('.category-btn').removeClass('active');
        $button.addClass('active');

        currentCategoryId = $button.data('category-id') || '';
        const search = $.trim($('#searchInput').val() || '');

        if (search) {
            searchMedicines(search);
        } else {
            loadMedicines();
        }
    });

    /* =========================================================
       REFRESH
       ========================================================= */

    $('#refreshBtn').on('click', function () {
        clearMessage();
        const search = $.trim($('#searchInput').val() || '');
        $.when(
            search ? searchMedicines(search) : loadMedicines(),
            loadCart()
        );
    });

    /* =========================================================
       DISCOUNT
       ========================================================= */

    $('#applyDiscount').on('click', function () {
        const discount = Number($('#discountAmount').val()) || 0;

        $.ajax({
            url: routes.updateCart,
            type: 'PATCH',
            data: {
                customer_id: cart?.customer_id || null,
                discount: discount,
                tax: Number(cart?.tax || 0),
                shipping: Number(cart?.shipping || 0),
                other_charges: Number(cart?.other_charges || 0),
                paid_amount: Number(cart?.paid_amount || 0),
                notes: cart?.notes || null
            },
            dataType: 'json'
        })
        .done(function (response) {
            cart = response.cart;
            renderCart();
            showToast('Discount applied successfully.', 'success');
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        });
    });

    /* =========================================================
       CLEAR CART
       ========================================================= */

    $('#clearCart').on('click', async function () {
        if (!cart?.items?.length) {
            showToast('Cart is already empty.', 'info');
            return;
        }

        const result = await Swal.fire({
            title: 'Clear Cart?',
            text: 'All items will be removed from the current cart.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Clear Cart',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Keep Cart'
        });

        if (!result.isConfirmed) return;

        $.ajax({
            url: routes.cancel,
            type: 'POST',
            dataType: 'json'
        })
        .done(function () {
            cart = null;
            renderCart();
            showToast('Cart cleared successfully.', 'success');
            loadCart();
        })
        .fail(function (xhr) {
            showToast(extractErrors(xhr), 'danger');
        });
    });

    /* =========================================================
       PAY NOW
       ========================================================= */

    $('#payNow').on('click', function () {
        if (!cart?.items?.length) {
            showToast('Please add at least one medicine to the cart.', 'warning');
            return;
        }
        openPaymentModal();
    });

    function openPaymentModal() {
        $('#paymentTotal').text(money(cart.grand_total));
        $('#paymentReceived').val(Number(cart.grand_total || 0).toFixed(2));
        $('#paymentCustomer').val(cart.customer_id || '');
        updatePaymentSummary();
        bootstrap.Modal.getOrCreateInstance($('#posPaymentModal')[0]).show();
    }

    /* =========================================================
       PAYMENT SUMMARY
       ========================================================= */

    function updatePaymentSummary() {
        const total = Number(cart?.grand_total || 0);
        const received = Number($('#paymentReceived').val()) || 0;
        const due = Math.max(total - received, 0);
        const change = Math.max(received - total, 0);
        $('#paymentDue').text(money(due));
        $('#paymentChange').text(money(change));
    }

    $('#paymentReceived').on('input', updatePaymentSummary);

    /* =========================================================
       CONFIRM PAYMENT / CHECKOUT
       ========================================================= */

    document.getElementById('confirmPayment')?.addEventListener('click', async function () {
        const button = this;
        const paidAmount = Number(document.getElementById('paymentReceived').value) || 0;
        const customerId = document.getElementById('paymentCustomer').value || null;

        if (paidAmount < 0) {
            showMessage('Payment amount cannot be negative.');
            return;
        }

        const receiptWindow = window.open(
            'about:blank',
            'pharmadesk_receipt',
            'width=420,height=700'
        );

        button.disabled = true;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Completing...
        `;

        try {
            // Save Payment Information
            const cartResponse = await request(routes.updateCart, {
                method: 'PATCH',
                body: JSON.stringify({
                    customer_id: customerId,
                    discount: Number(cart.discount || 0),
                    tax: Number(cart.tax || 0),
                    shipping: Number(cart.shipping || 0),
                    other_charges: Number(cart.other_charges || 0),
                    paid_amount: paidAmount,
                    notes: cart.notes || null
                })
            });

            cart = cartResponse.cart;

            // Complete Sale
            const response = await request(routes.checkout, {
                method: 'POST',
                body: JSON.stringify({
                    customer_id: customerId,
                    sale_date: new Date().toISOString().slice(0, 10),
                    paid_amount: paidAmount,
                    notes: cart.notes || null
                })
            });

            if (response.success && response.receipt_url) {
                // Send receipt URL to already-open window
                if (receiptWindow) {
                    receiptWindow.location.href = response.receipt_url;
                } else {
                    window.open(response.receipt_url, '_blank');
                }

                // Close POS payment modal
                bootstrap.Modal.getInstance(document.getElementById('posPaymentModal'))?.hide();

                // ✅ RELOAD THE PAGE TO REFRESH CART AND MEDICINE STOCK
                setTimeout(function() {
                    location.reload();
                }, 500);

                return;
            }

            if (receiptWindow) {
                receiptWindow.close();
            }

            showMessage(response.message || 'Sale completed successfully.', 'success');

        } catch (error) {
            if (receiptWindow) {
                receiptWindow.close();
            }
            showMessage(extractErrors(error));
            button.disabled = false;
            button.innerHTML = `
                <i class="bi bi-check-circle me-1"></i>
                Complete Sale
            `;
        }
    });

    /* =========================================================
       INITIAL LOAD
       ========================================================= */

    loadMedicines();
    loadCart();

});
</script>
