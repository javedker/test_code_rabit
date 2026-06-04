@extends('layout.master')

@section('title', 'Your Orders')

@section('content')
    {{-- Error panel --}}
    <x-breadcrumb current="Account" title="Orders" />
    <div id="orders-error" class="container col-md-6 offset-md-3 mt-5">
        <div class="p-4 text-center" role="alert">
            <div class="mb-3">
                <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
            </div>
            <h4 id="orders-error-message" class="alert-heading fw-bold">
                Something went wrong!
            </h4>
            <hr>
            <div class="tptrack__btn mt-4">
                <a href="{{ route('articles') }}">
                    <button class="tptrack__submition">
                        <i class="fal fa-long-arrow-left mx-2"></i>Continue Shopping
                    </button>
                </a>
            </div>
        </div>
    </div>

    {{-- Orders table --}}
    <div id="orders-listing" class="container py-5 track-area">
        {{-- <h2 class="text-center mb-4">Your Orders</h2> --}}

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-primary-bg">
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                {{-- placeholders while loading --}}
                <tbody id="orders-placeholder" class="placeholder-glow">
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td><span class="placeholder col-3"></span></td>
                            <td><span class="placeholder col-2"></span></td>
                            <td><span class="placeholder col-2"></span></td>
                            <td><span class="placeholder col-3"></span></td>
                            <td><span class="placeholder col-1"></span></td>
                        </tr>
                    @endfor
                </tbody>

                {{-- real data --}}
                <tbody id="orders-container" style="display:none"></tbody>

                {{-- no-orders message --}}
                <tbody id="orders-empty" style="display:none">
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            No orders found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/orders.js') }}"></script>
@endpush