@extends('layout.master')


@section('title', 'Home')
@section('content')


    <x-banners />
    <x-categories-slider />
    <x-services />
    <div class="" id="new-arrivals"></div>
    <x-brands-slider />
    <section class="platinam-product-area pt-50 ">
        <div class="container">
            <div class="mb-3">
                <div class="row align-items-center mb-3">
                    <div class="col text-center">
                        <h3 class="mb-0 text-uppercase mx-3">New Arrivals</h3>
                    </div>
                    <div class="col-auto">
                        <!-- Same arrow style as Popular Products -->
                        <div class="tpplatiarrow d-flex align-items-center justify-content-end">
                            <div class="cat-prev me-2"><i class="far fa-chevron-left"></i></div>
                            <div class="cat-next"><i class="far fa-chevron-right"></i></div>
                        </div>
                    </div>
                </div>



                <div class="swiper-container platinam-pro-active">



                    <div class="swiper-wrapper">

                        @foreach (data_get($articles, 'data', []) as $article)
                            <div class="swiper-slide">
                                <div class="tpproduct tpproductitem mb-15 p-relative">
                                    <div class="tpproduct__thumb">
                                        <div class="tpproduct__thumbitem p-relative">
                                            <a href="{{ route('articles.details', $article['slug']) }}">
                                                <img src="{{ route('media', ['img' => setMedia($article['thumbnail'])]) }}"
                                                    alt="product-thumb">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="tpproduct__content-area text-center">
                                        <b>{{ $article['brand_name'] }}</b>
                                        <h3 class="tpproduct__title mb-5">
                                            <a href="{{ route('articles.details', $article['slug']) }}">
                                                {{ $article['name'] }}
                                            </a>
                                        </h3>
                                        <div class="tpproduct__priceinfo p-relative">
                                            <b class="text-primary">
                                                <span>OMR {{ number_format($article['price'], 3) }}</span>
                                            </b>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="special-offers"></div>

                </div>
            </div>
    </section>


    <x-offers />
    {{-- <x-offer-prices /> --}}


@endsection
