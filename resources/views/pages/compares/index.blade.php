@extends('layout.master')

@section('title', 'Compare Products')

@php
    $labelColW = 220;   // px
    $prodColW = 260;   // px
    $prodCount = count($compares ?? []);
    $minWidth = $labelColW + ($prodColW * max($prodCount, 1));
@endphp


@section('content')
    <section class="mt-3" id="main-compare-div">
        <div class="container bg-white">

            @if(!empty($compares) && count($compares) > 0)
                <x-breadcrumb title="Shop" current="Compare Product" />
                <h2 class="text-left mb-4" style="font-weight: bold;">Compare Products</h2>

                @php
                    $features = [
                        'Model No' => 'model_no',
                        'Category' => 'category',
                        'Type' => 'type',
                        'Brand' => 'brand',
                        'Segment' => 'segment',
                        'Capacity (BTU)' => 'capacity_btu',
                        'Compressor' => 'compressor',
                        'Features' => 'features',
                        'Origin' => 'origin',
                        'Warranty' => 'warranty',
                        'Free Shipping' => 'free_shipping',
                    ];

                @endphp

                {{-- Single horizontal scroller --}}
                <div class="compare-scroll">
                    <div class="compare-table" style="min-width: {{ $minWidth }}px;">

                        {{-- Header row: product cards --}}
                        <div class="compare-row">
                            <div class="compare-label" style="width: {{ $labelColW }}px;">&nbsp;</div>

                            @foreach($compares as $product)
                                <div class="compare-col" style="width: {{ $prodColW }}px;">
                                    <div class="compare-card compare-card--fixed">
                                        <a class="remove-link text-danger remove-from-compare" style="cursor: pointer;"
                                            data-slug="{{ $product['slug'] }}" title="Remove">
                                            <i class="fa fa-times"></i> Remove
                                        </a>

                                        <img src="{{route('media', ['img' => setMedia($product['thumbnail'])]) }}"
                                            alt="{{ $product['name'] }}" class="img-responsive">

                                        <h5 class="mt-2" style="height: 30px;">{{ $product['name'] }}</h5>


                                        <div class="tpproduct-details__price mt-5">
                                            <span style="font-size:20px;">{!!formatePrice($product['price'], 'oman', true)!!}</span>

                                            {{-- @if ($article['discount'] > 0.0)
                                            <del>{!!formatePrice($article['base_price'], 'oman', true)!!}</del>
                                            @endif --}}
                                        </div>

                                        <div class="compare-card__actions">
                                            <button class="mt-1 user-account-btn bordered rounded btn-add-to-cart"
                                                data-slug="{{$product['slug']}}">
                                                Add To Cart
                                            </button>
                                        </div>
                                        <button class="user-account-btn bordered rounded  px-5"
                                            id="compare-spinner-{{$product['slug']}}" style="display: none;">
                                            <div class="spinner-border text-primary" role="status"
                                                style="width: 1.5rem; height: 1.5rem;">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                        <div class="compare-card__actions" id="compare-go-to-cart-{{$product['slug']}}"
                                            style="display: none;">
                                            <a class="mt-1 user-account-btn bordered rounded " href="{{route('cart')}}">
                                                Go To Cart
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>

                        {{-- Feature rows (share the SAME scroller) --}}
                        @foreach($features as $label => $field)
                            <div class="compare-row">
                                <div class="compare-label" style="width: {{ $labelColW }}px;">{{ $label }}</div>

                                @foreach($compares as $product)
                                    <div class="compare-col" style="width: {{ $prodColW }}px;">
                                        {!! $product[$field] ?? '<span class="text-muted">N/A</span>' !!}
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                </div>

            @else
                {{-- Empty State --}}
                <div class="m-5">
                    <div id="" style="display:block; min-height:300px; border:2px dotted #ccc; padding:40px;"
                        class="text-center mt-5 mb-5 flex-column justify-content-center align-items-center col-6 offset-3">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Your Compare List Is Empty</h4>
                        <div class="tptrack__btn mt-4 ">
                            <a href="{{ route('articles') }}">
                                <button class="tptrack__submition">
                                    <i class="fal fa-long-arrow-left mx-2"></i>Continue Shopping
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/compares.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        // Ensure scroller starts at left (iOS/Android quirk sometimes)
        (function () {
            var scroller = document.querySelector('.compare-scroll');
            if (scroller) scroller.scrollLeft = 0;
        })();
    </script>
@endpush