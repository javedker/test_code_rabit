@extends('layout.master')

@section('title', $article['name'])

@section('content')
    <x-breadcrumb title="{{ $article['brand_name'] }}" titleUrl="{{ route('articles', ['brand' => $article['brand_name']]) }}"
        isTitle="true" current="Shop" />
    <section class="product-area pt-80 pb-50" id="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="tpproduct-details__list-img d-none d-md-block">
                        @foreach ($article['media'] as $media)
                            <div class="tpproduct-details__list-img-item mb-2">
                                <img src="{{ route('media', ['img' => setMedia($media)]) }}" alt="">
                            </div>
                        @endforeach
                    </div>

                    {{-- 📱 Mobile/Tablet View: Swiper Slider --}}
                    <div class="tpproduct-details__list-img swiper d-block d-md-none" id="product-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($article['media'] as $media)
                                <div class="swiper-slide tpproduct-details__list-img-item">
                                    <img src="{{ route('media', ['img' => setMedia($media)]) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-7">
                    <div class="tpproduct-details__content tpproduct-details__sticky">
                        <div class="tpproduct-details__tag-area d-flex align-items-center mb-5">
                            <span class="">{{ $article['brand_name'] }}</span>

                        </div>

                        <div class="tpproduct-details__title-area d-flex align-items-center flex-wrap ">
                            <h3 class="tpproduct-details__title">{{ $article['name'] }}</h3>
                        </div>

                        @if ($article['isStockOut'])
                            <span class="tpproduct-details__stock--out mx-1">Unavailable </span>
                        @else
                            <span class="tpproduct-details__stock mx-1 ">Available</span>
                        @endif
                        @if ($article['discount'] > 0.0)
                            <span class="sale-price badge rounded-0 bg-danger-2">
                                {{ round($article['discount']) }}%
                                {{-- VAT BACK --}}
                            </span>
                        @endif
                        <div class="tpproduct-details__price mb-30 mt-3">

                            @if ($article['discount'] > 0.0)
                                <del>{!! formatePrice($article['base_price'] * 1.05, 'oman', true) !!}</del>
                            @endif
                            <span>{!! formatePrice($article['price'], 'oman', true) !!}</span> (Incl. VAT) <br> (Supply Only)
                        </div>

                        {{-- <div class="tpproduct-details__pera">
                            <p>Priyoshop has brought to you the Hijab 3 Pieces Combo Pack PS23. It is a <br>completely
                                modern design and you feel comfortable to put on this hijab. <br>Buy it at the best price.
                            </p>
                        </div> --}}
                        <div class="tpproduct-details__count d-flex align-items-center flex-wrap mb-25">
                            {{-- <div class="tpproduct-details__quantity">
                                <span class="cart-minus"><i class="far fa-minus"></i></span>
                                <input class="tp-cart-input" type="text" value="1">
                                <span class="cart-plus"><i class="far fa-plus"></i></span>
                            </div> --}}

                            @if ($article['isStockOut'])
                                <div class="alert alert-info mt-3">
                                    <p>Currently Product is Unavailable</p>

                                    <div class="form-check mt-2" id="notify-me-div">
                                        <input type="checkbox" class="form-check-input"
                                            value="{{ $article['sap_article_number'] }}" id="notifyMeCheckbox" />
                                        <label class="form-check-label" for="notifyMeCheckbox">
                                            Notify me when available
                                        </label>
                                    </div>
                                    <div class="mt-2" id="notify-me-spinner-div" style="display: none;">
                                        <div class="spinner-border text-primary" role="status"
                                            style="width: 1.5rem; height: 1.5rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        Please Wait...
                                    </div>

                                </div>
                            @else
                                <a class="tpproduct-details__cart" id="add-to-cart" data-slug='{{ $article['slug'] }}'>
                                    <button><i class="fal fa-shopping-cart"></i> Add To Cart</button>
                                </a>
                                <a class="tpproduct-details__cart active" href="{{ route('cart') }}" style="display: none;"
                                    id="got-to-cart-details">
                                    <button><i class="fal fa-shopping-cart"></i> Go To Cart</button>
                                </a>
                                <div class="tpproduct-details__cart " style="display: none;"
                                    id="add-to-card-spinner-details">
                                    <button>
                                        <span class="spinner-border spinner-border-sm" id="loginSpinner" role="status"
                                            aria-hidden="true"></span>
                                        Please Wait...
                                    </button>
                                </div>
                            @endif



                            <a class="tpproduct-details__wishlist  ml-20" id="remove-from-favorite"
                                title="Remove from Favorite" data-slug='{{ $article['slug'] }}'
                                style="display: {{ $article['isFavorite'] ? 'block' : 'none' }}">
                                <button><i class="fas fa-heart"></i></button>
                            </a>
                            <a class="tpproduct-details__wishlist ml-20" id="add-to-favorite" title="Add To Favorite"
                                style="display: {{ !$article['isFavorite'] ? 'block' : 'none' }}"
                                data-slug='{{ $article['slug'] }}'>
                                <button><i class="fal fa-heart"></i></button>
                            </a>

                            <a class=" mx-4" id="add-to-favorite-spinner" style="display: none;">
                                <div class="spinner-border" style="margin-top:10px;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </a>
                            <a class="tpproduct-details__wishlist ml-20" id="add-to-compare" title="Compares"
                                data-slug='{{ $article['slug'] }}'>
                                <button><i class="fas fa-exchange-alt"></i></button>
                            </a>
                            <a class=" mx-4" id="add-to-compare-spinner" style="display: none;">
                                <div class="spinner-border" style="margin-top:10px;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </a>
                        </div>
                        {{-- <div class="tpproductdot mb-30">
                            <a class="tpproductdot__variationitem" href="#">
                                <div class="tpproductdot__termshape">
                                    <span class="tpproductdot__termshape-bg"></span>
                                    <span class="tpproductdot__termshape-border"></span>
                                </div>
                            </a>
                            <a class="tpproductdot__variationitem" href="#">
                                <div class="tpproductdot__termshape">
                                    <span class="tpproductdot__termshape-bg red-product-bg"></span>
                                    <span class="tpproductdot__termshape-border red-product-border"></span>
                                </div>
                            </a>
                            <a class="tpproductdot__variationitem" href="#">
                                <div class="tpproductdot__termshape">
                                    <span class="tpproductdot__termshape-bg orange-product-bg"></span>
                                    <span class="tpproductdot__termshape-border orange-product-border"></span>
                                </div>
                            </a>
                            <a class="tpproductdot__variationitem" href="#">
                                <div class="tpproductdot__termshape">
                                    <span class="tpproductdot__termshape-bg purple-product-bg"></span>
                                    <span class="tpproductdot__termshape-border purple-product-border"></span>
                                </div>
                            </a>
                        </div> --}}



                        @if ($article['category'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Category:</p><span>{{ $article['category'] }}</span>
                            </div>
                        @endif

                        @if ($article['segment'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Segment:</p><span>{{ trim($article['segment']) }}</span>
                            </div>
                        @endif

                        @if ($article['brand_name'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Brand:</p><span>{{ trim($article['brand_name']) }}</span>
                            </div>
                        @endif

                        @if ($article['type'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Type:</p><span>{{ trim($article['type']) }}</span>
                            </div>
                        @endif

                        @if ($article['model_no'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Model No:</p><span>{{ trim($article['model_no']) }}</span>
                            </div>
                        @endif

                        @if ($article['sap_article_number'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Article:</p><span>{{ $article['sap_article_number'] }}</span>
                            </div>
                        @endif



                        @if ($article['origin'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Origin:</p><span>{{ trim($article['origin']) }}</span>
                            </div>
                        @endif

                        @if ($article['warranty'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Warranty:</p><span>{{ trim($article['warranty']) }}</span>
                            </div>
                        @endif


                        @if ($article['capacity_btu'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Capacity (BTU):</p><span>{{ $article['capacity_btu'] }}</span>
                            </div>
                        @endif

                        @if ($article['compressor'])
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Compressor:</p><span>{{ $article['compressor'] }}</span>
                            </div>
                        @endif

                        @if (config('app.is_show_stock'))
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Stock:</p><span>{{ $article['stock'] }}</span>
                            </div>
                        @endif

                        @if ($article['features'] && str_contains($article['features'], ',') && strlen($article['features']) > 100)
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Features:</p>
                                <br>
                                <span>
                                    {!! str_replace(',', '<br> • ', '• ' . trim($article['features'])) !!}
                                </span>

                            </div>
                        @else
                            <div class="tpproduct-details__information tpproduct-details__code">
                                <p>Features:</p>
                                <span>
                                    {{ trim($article['features']) }}
                                </span>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-lg-2 col-md-5 ">
                    <div class="tpproduct-details__condation mt-100">
                        <ul>
                            @if (isset($article['free_shipping']))
                                <li>
                                    <div class="tpproduct-details__condation-item d-flex align-items-center">
                                        <div class="tpproduct-details__condation-thumb">
                                            <img src="{{ asset('img/icon/product-det-1.png') }}" alt="Free shipping"
                                                class="tpproduct-details__img-hover">
                                        </div>
                                        <div class="tpproduct-details__condation-text">
                                            <p><strong>Free shipping</strong><br>{{ $article['free_shipping'] }}.</p>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li>
                                <div class="tpproduct-details__condation-item d-flex align-items-center">
                                    <div class="tpproduct-details__condation-thumb">
                                        <img src="{{ asset('img/svg/services02.svg') }}" alt="Free Returns"
                                            class="tpproduct-details__img-hover">
                                    </div>
                                    <div class="tpproduct-details__condation-text">
                                        <p><strong>Free Returns</strong><br>14-days free return policy</p>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="tpproduct-details__condation-item d-flex align-items-center">
                                    <div class="tpproduct-details__condation-thumb">
                                        <img src="{{ asset('img/svg/services03.svg') }}" alt="Secured Payments"
                                            class="tpproduct-details__img-hover">
                                    </div>
                                    <div class="tpproduct-details__condation-text">
                                        <p><strong>Secured Payments</strong><br>Via Bank Muscat Payment Gateway</p>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="tpproduct-details__condation-item d-flex align-items-center">
                                    <div class="tpproduct-details__condation-thumb">
                                        <img src="{{ asset('img/svg/services04-1.svg') }}" width="50"
                                            alt="Customer Service" class="tpproduct-details__img-hover">
                                    </div>
                                    <div class="tpproduct-details__condation-text">
                                        <p><strong>Customer Service</strong><br>Top notch customer service</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- product-area-end -->

    <!-- product-details-area-start -->
    {{-- <div class="product-setails-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tpproduct-details__navtab mb-60">
                        <div class="tpproduct-details__nav mb-30">
                            <ul class="nav nav-tabs pro-details-nav-btn" id="myTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-links active" id="home-tab-1" data-bs-toggle="tab"
                                        data-bs-target="#home-1" type="button" role="tab" aria-controls="home-1"
                                        aria-selected="true">Description</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-links" id="information-tab" data-bs-toggle="tab"
                                        data-bs-target="#additional-information" type="button" role="tab"
                                        aria-controls="additional-information" aria-selected="false">Additional
                                        information</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-links" id="reviews-tab" data-bs-toggle="tab"
                                        data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews"
                                        aria-selected="false">Reviews
                                        (2)</button>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content tp-content-tab" id="myTabContent-2">
                            <div class="tab-para tab-pane fade show active" id="home-1" role="tabpanel"
                                aria-labelledby="home-tab-1">
                                <p class="mb-30">In marketing a product is an object or system made available for consumer
                                    use it is anything that can be offered to a market to satisfy the desire or need of a
                                    customer. In retailing, products are often referred to as
                                    merchandise, and in manufacturing, products are bought as raw materials and then sold as
                                    finished goods. A service is also regarded to as a type of product. Commodities are
                                    usually raw materials such as metals
                                    and agricultural products, but a commodity can also be anything widely available in the
                                    open market. In project management, products are the formal definition of the project
                                    deliverables that make up contribute
                                    to delivering the objectives of the project.</p>
                                <p>A product can be classified as tangible or intangible. A tangible product is a physical
                                    object that can be perceived by touch such as a building, vehicle, gadget, or clothing.
                                    An
                                    intangible product is a product that
                                    can only be perceived indirectly such as an insurance policy. Services can be broadly
                                    classified under intangible products which can be durable or non durable. A product line
                                    is "a group of products that are
                                    closely related, either because they function in a similar manner, are sold to the same
                                    customer groups, are marketed through the same types of outlets, or fall within given
                                    price ranges."Many businesses offer a
                                    range of product lines which may be unique to a single organisation or may be common
                                    across the business's industry. In 2002 the US Census compiled revenue figures for the
                                    finance and insurance industry by
                                    various product lines such as "accident, health and medical insurance premiums" and
                                    "income from secured consumer loans.</p>
                            </div>
                            <div class="tab-pane fade" id="additional-information" role="tabpanel"
                                aria-labelledby="information-tab">
                                <div class="product__details-info table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td class="add-info">Weight</td>
                                                <td class="add-info-list"> 2 lbs</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Dimensions</td>
                                                <td class="add-info-list"> 12 × 16 × 19 in</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Product</td>
                                                <td class="add-info-list"> Purchase this product on rag-bone.com</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Color</td>
                                                <td class="add-info-list"> Gray, Black</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Size</td>
                                                <td class="add-info-list"> S, M, L, XL</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Model</td>
                                                <td class="add-info-list"> Model </td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Shipping</td>
                                                <td class="add-info-list"> Standard shipping: $5,95L</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Care Info</td>
                                                <td class="add-info-list"> Machine Wash up to 40ºC/86ºF Gentle Cycle</td>
                                            </tr>
                                            <tr>
                                                <td class="add-info">Brand</td>
                                                <td class="add-info-list"> Kazen</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="product-details-review">
                                    <h3 class="tp-comments-title mb-35">3 reviews for “Wide Cotton Tunic extreme hammer”
                                    </h3>
                                    <div class="latest-comments mb-55">
                                        <ul>
                                            <li>
                                                <div class="comments-box d-flex">
                                                    <div class="comments-avatar mr-25">
                                                        <img src="assets/img/shop/reviewer-01.png" alt="">
                                                    </div>
                                                    <div class="comments-text">
                                                        <div
                                                            class="comments-top d-sm-flex align-items-start justify-content-between mb-5">
                                                            <div class="avatar-name">
                                                                <b>Siarhei Dzenisenka</b>
                                                                <div class="comments-date mb-20">
                                                                    <span>March 27, 2018 9:51 am</span>
                                                                </div>
                                                            </div>
                                                            <div class="user-rating">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fal fa-star"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <p class="m-0">This is cardigan is a comfortable warm classic piece.
                                                            Great
                                                            to layer with a light top and you can dress up or down given the
                                                            jewel
                                                            buttons. I'm 5'8” 128lbs a 34A and the Small fit fine.</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="comments-box d-flex">
                                                    <div class="comments-avatar mr-25">
                                                        <img src="assets/img/shop/reviewer-02.png" alt="">
                                                    </div>
                                                    <div class="comments-text">
                                                        <div
                                                            class="comments-top d-sm-flex align-items-start justify-content-between mb-5">
                                                            <div class="avatar-name">
                                                                <b>Tommy Jarvis </b>
                                                                <div class="comments-date mb-20">
                                                                    <span>March 27, 2018 9:51 am</span>
                                                                </div>
                                                            </div>
                                                            <div class="user-rating">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fal fa-star"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <p class="m-0">This is cardigan is a comfortable warm classic piece.
                                                            Great
                                                            to layer with a light top and you can dress up or down given the
                                                            jewel
                                                            buttons. I'm 5'8” 128lbs a 34A and the Small fit fine.</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="comments-box d-flex">
                                                    <div class="comments-avatar mr-25">
                                                        <img src="assets/img/shop/reviewer-03.png" alt="">
                                                    </div>
                                                    <div class="comments-text">
                                                        <div
                                                            class="comments-top d-sm-flex align-items-start justify-content-between mb-5">
                                                            <div class="avatar-name">
                                                                <b>Johnny Cash</b>
                                                                <div class="comments-date mb-20">
                                                                    <span>March 27, 2018 9:51 am</span>
                                                                </div>
                                                            </div>
                                                            <div class="user-rating">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                    <li><a href="#"><i class="fal fa-star"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <p class="m-0">This is cardigan is a comfortable warm classic piece.
                                                            Great
                                                            to layer with a light top and you can dress up or down given the
                                                            jewel
                                                            buttons. I'm 5'8” 128lbs a 34A and the Small fit fine.</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product-details-comment">
                                        <div class="comment-title mb-20">
                                            <h3>Add a review</h3>
                                            <p>Your email address will not be published. Required fields are marked*</p>
                                        </div>
                                        <div class="comment-rating mb-20 d-flex">
                                            <span>Overall ratings</span>
                                            <ul>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                <li><a href="#"><i class="fal fa-star"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="comment-input-box">
                                            <form action="#">
                                                <div class="row">
                                                    <div class="col-xxl-12">
                                                        <div class="comment-input">
                                                            <textarea placeholder="Your review..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-6">
                                                        <div class="comment-input">
                                                            <input type="text" placeholder="Your Name*">
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-6">
                                                        <div class="comment-input">
                                                            <input type="email" placeholder="Your Email*">
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-12">
                                                        <div class="comment-submit">
                                                            <button type="submit" class="tp-btn pro-submit">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <x-related-products related="{{ json_encode($article['related']) }}" />


@endsection


@push('scripts')
    <script src="{{ asset('js/articles.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/favorites.js') }}"></script>
    <script src="{{ asset('js/compares.js') }}"></script>
@endpush
