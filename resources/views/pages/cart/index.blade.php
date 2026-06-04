@extends('layout.master')

@section('title', 'Cart')


@section('content')

    <section class="track-area cart-area pt-80 pb-40">
        <div class="container">
            <div class="" id="main-cart-parent-div">
                <div class="table-content table-responsive">
                    <table class="table">
                        
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                {{-- <th>Unit Price</th> --}}
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="main-cart-div">
                            <!-- dynamic rows from cart.js -->
                        </tbody>
                    </table>

                </div>
                <div class="row  mt-2">
                    <div class="col-md-3 mb-2">
                        <a href="" id="main-clear-cart">
                            <button class="tptrack__submition">
                                <i class="fal fa-trash-alt mx-2"></i>Clear Cart
                            </button>
                        </a>
                        <div id="main-clear-cart-spinner" style="display: none;">
                            <button class="tptrack__submition" type="button">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            </button>
                        </div>

                    </div>
                    <div class="col-md-6"></div>

                    <div class="col-md-3 text-end">
                        <a href="{{ route('checkout') }}" class="col-2 w-100" style="display: none;"
                            id="checkout-proceed-button">
                            <button class="tptrack__submition">
                                Proceed Checkout <i class="fal fa-long-arrow-right mx-2"></i>
                            </button>
                        </a>
                        <span id="checkout-proceed-button-error" style="display: none;" class="tptrack__submition">
                            Unable to proceed
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div id="main-empty-cart-div" style="display: none; min-height: 300px; border: 2px dotted #ccc; padding: 40px;"
            class="text-center mt-5  flex-column justify-content-center align-items-center col-6 offset-3">

            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is currently empty</h4>

            <div class="tptrack__btn  mt-4">
                <a href="{{ route('articles') }}">
                    <button class="tptrack__submition">
                        <i class="fal fa-long-arrow-left mx-2"></i>Continue Shopping
                    </button>
                </a>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script src="{{asset('js/cart.js')}}"></script>
@endpush