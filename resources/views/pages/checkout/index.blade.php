@extends('layout.master')

@section('title', 'Checkout')
@section('content')
    <div id="checkout-error" class=" pb-50 mt-10" style="display:none">
        <div class="" role="alert">

            <h4 class="alert-heading"> <i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong
            </h4>
            <p id="checkout-error-message" class="text-center">
                <!-- your JS will inject the error message here -->
            </p>
            <hr>
            <div class="tptrack__btn  mt-4">
                <a href="{{ route('articles') }}">
                    <button class="tptrack__submition">
                        <i class="fal fa-long-arrow-left mx-2"></i>Continue Shopping
                    </button>
                </a>
            </div>
        </div>
    </div>
    <div class="checkout-area pb-50 mt-10" id="checkout-div">
        <div class="container">

            <div class="row">

                {{-- Left Side: Address Selection --}}
                <div class="col-lg-6 col-md-12">
                    <div class="checkbox-form">
                        <div class="col-8 border-bottom">
                            <h3>Select Delivery Address</h3>
                        </div>

                        <div class="addresses-wrapper">
                            <div class="addresses-list">


                            </div>
                            <div class="add-address-btn mt-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="fal fa-plus"></i> Add New Delivery Address
                                </a>
                            </div>
                        </div>

                        <a href="#" data-bs-toggle="modal" id="add-new-address-div" style="display: none"
                            data-bs-target="#addAddressModal">
                            <div class="alert alert-info">
                                Please add Delivery Address to place order.
                            </div>
                            <div class="addresses-card card text-center">
                                <i class="fal fa-plus"></i>
                                <h6>Add New Delivery Address</h6>
                            </div>
                        </a>

                    </div>
                </div>

                {{-- Right Side: Order Summary --}}
                <div class="col-lg-6 col-md-12">
                    <div class="your-order">

                        <h3>Your Order Preview</h3>
                        <div class="your-order-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Product</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Amt. W/o VAT</th>
                                        <th class="text-end">VAT</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="order-summary"></tbody>
                            </table>

                            <div class="summary-totals mt-3">
                                <div class="row mb-1">
                                    <div class="col-md-6">Order Amount Without VAT</div>
                                    <div class="col-md-6 text-end" id="subTotal"> </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-6">VAT (5%)</div>
                                    <div class="col-md-6 text-end" id="totalTax"> </div>
                                </div>
                                <div class="row border-top pt-2">
                                    <div class="col-md-6 "><span class="h5">Order Total</span> (Incl. VAT) (Supply Only)
                                    </div>
                                    <div class="col-md-6 text-end " id="">
                                        <span class="h5" id="finalTotal"></span>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="place-order-btn-error-div" class="order-button-payment mt-4" style="display:none;">
                            <span class="alert btn-danger w-100">
                                Unable to proceed — please review your cart items
                            </span>
                        </div>
                        <div class="text-primary start-start mt-2">
                            Order confirmation is subject to successful payment confirmation.
                        </div>
                        <div class="mt-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input text-danger" required type="checkbox" value="yes"
                                    id="is_terms_and_condition" name="is_terms_and_condition"
                                    oninvalid="this.setCustomValidity('Please agree to the terms and conditions')"
                                    onchange="this.setCustomValidity('');">
                                <label class="form-check-label" for="Conditions">
                                    I have read and agree to the <a href="{{ route('home.terms') }}" class="text-primary"
                                        target="_blank">Terms & Conditions <span class="text-danger">*</span> </a>
                                </label>
                            </div>
                        </div>
                        <div id="place-order-btn-parent-div" style="display: none;">
                            <div class="order-button-payment mt-4" id="place-order-btn-div">
                                <button type="submit" id="place-order-btn" class="tp-btn tp-color-btn w-100">Place Order <i
                                        class="fal fa-long-arrow-right"></i></button>
                            </div>
                            <div class="order-button-payment mt-4" id="place-order-spinner" style="display: none;">
                                <button type="submit" class="tp-btn tp-color-btn w-100" id="place-order-btn" disabled
                                    style="cursor: not-allowed">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    <span id="place-order-spinner-text"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="" id="order-id">
    <x-address-modal />
    {{-- <x-loader /> --}}

@endsection

@push('scripts')
    <script src="{{asset('js/checkout.js')}}"></script>
    <script src="{{asset('js/addresses.js')}}"></script>
@endpush