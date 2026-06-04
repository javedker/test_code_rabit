<div class="tpcartinfo tp-cart-info-area p-relative">
    <button class="tpcart__close"><i class="fal fa-times"></i></button>
    <div class="tpcart">
        <h4 class="tpcart__title">Your Cart</h4>

        <div class="tpcart__product" id="header-cart-main-div">
            <div class="tpcart__product-list">
                <ul id="header-cart-div">
                </ul>
            </div>
            <div class="tpcart__checkout">
                <div class="tpcart__total-price d-flex justify-content-between align-items-center">
                    <span> Subtotal:</span>
                    <span class="heilight-price" id="sidebar-total"></span>
                </div>
                <div class="tpcart__checkout-btn">
                    <a class="tpcart-btn mb-10" href="{{route('cart')}}">View Cart</a>
                    <a class="tpcheck-btn mb-10" href="{{route('checkout')}}">Checkout</a>
                    <a class="tpcheck-btn-secondary mb-10" id="header-clear-cart" style="cursor: pointer;"> <i
                            class="fal fa-trash-alt mx-2"></i>
                        Clear Cart</a>
                    <span class="tpcheck-btn-secondary" id="header-clear-cart-spinner" style="display: none;"> <span
                            class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    </span>
                </div>
            </div>
        </div>

        <div id="header-cart-main-empty-div" style="display: none;" class="tpcart__product">
            <div class="tpcart__empty text-center py-4">
                <span class="fa-stack fa-2x mb-2">
                    <i class="fal fa-shopping-cart fa-stack-1x"></i>
                </span>
                <h6 class="mt-2 mb-1">Your cart is empty</h6>
                <p class="mb-3 text-muted small">Add items to get started.</p>
            </div>
        </div>



    </div>
</div>
<div class="cartbody-overlay"></div>