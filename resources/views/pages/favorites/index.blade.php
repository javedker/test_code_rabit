@extends('layout.master')

@section('title', 'Favorites')

@section('content')

    <div class="container">
        <x-breadcrumb title="Shop" current="Favorites" />
        <h2 class="text-left  mx-2" style="font-weight: bold;">Favorites</h2>
    </div>
    <section class="track-area cart-area pt-25 pb-40">
        <div class="container">

            <div class="table-content table-responsive" id="main-fav-parent-div">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Add to Cart</th>
                            <th>Remove</th>
                        </tr>
                    </thead>

                    <tbody id="main-fav-div">
                        {{-- JavaScript will load favorites here --}}
                    </tbody>
                </table>
            </div>

            <div id="main-empty-fav-div" style="min-height: 300px; border: 2px dotted #ccc; padding: 40px; display: none;"
                class="text-center mt-5 flex-column justify-content-center align-items-center col-6 offset-3">
                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Your favorites list is empty</h4>
                <div class="tptrack__btn mt-4">
                    <a href="{{ route('articles') }}">
                        <button class="tptrack__submition">
                            <i class="fal fa-long-arrow-left mx-2"></i>Continue Shopping
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/favorites.js') }}"></script>
@endpush