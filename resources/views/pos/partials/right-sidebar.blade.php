<aside class="cart-panel">

    <div class="cart-card">

        <div class="cart-title">
            Your Cart
        </div>


        <div
            class="order-number"
            id="cartNumber"
        >
            Cart #{{ str_pad($cart->id, 6, '0', STR_PAD_LEFT) }}
        </div>


        <div
            class="cart-items"
            id="cartItems"
        >

            @forelse ($cart->items as $item)

                <div
                    class="cart-item"
                    data-cart-item-id="{{ $item->id }}"
                >

                    <div class="cart-thumb">

                        <div class="medicine-pack pack-blue"></div>

                    </div>


                    <div>

                        <div class="cart-product-name">
                            {{ $item->medicine->name }}
                        </div>


                        <div class="quantity">

                            <button
                                type="button"
                                class="qty-btn cart-decrease"
                                data-id="{{ $item->id }}"
                            >
                                −
                            </button>


                            <span class="qty-value">
                                {{ $item->quantity }}
                            </span>


                            <button
                                type="button"
                                class="qty-btn cart-increase"
                                data-id="{{ $item->id }}"
                            >
                                +
                            </button>

                        </div>

                    </div>


                    <div class="cart-actions">

                        <button
                            type="button"
                            class="remove-item cart-remove"
                            data-id="{{ $item->id }}"
                            title="Remove item"
                            aria-label="Remove {{ $item->medicine->name }}"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>


                        <div class="cart-price">
                            Rs.
                            {{ number_format(
                                $item->total,
                                2
                            ) }}
                        </div>

                    </div>

                </div>

            @empty

                <div
                    class="empty-cart"
                    id="emptyCart"
                >

                    <i class="bi bi-cart-x"></i>

                    <div>
                        Your cart is empty
                    </div>

                </div>

            @endforelse

        </div>


        <div class="summary">

            <div class="summary-row">

                <span>Subtotal:</span>

                <span id="cartSubtotal">
                    Rs. {{ number_format($cart->subtotal, 2) }}
                </span>

            </div>


            <div class="summary-row">

                <span>Discount:</span>

                <span id="cartDiscount">
                    - Rs. {{ number_format($cart->discount, 2) }}
                </span>

            </div>


            <div class="summary-row total">

                <span>Total:</span>

                <span id="cartTotal">
                    Rs. {{ number_format($cart->grand_total, 2) }}
                </span>

            </div>

        </div>


        <div class="input-group discount-input">

            <input
                type="number"
                id="discountAmount"
                class="form-control"
                step="0.01"
                min="0"
                placeholder="Discount Amount"
                value="{{ $cart->discount }}"
            >


            <button
                type="button"
                class="btn btn-success"
                id="applyDiscount"
            >
                Apply
            </button>

        </div>

    </div>

</aside>