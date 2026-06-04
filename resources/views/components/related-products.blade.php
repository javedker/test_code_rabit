<div class="related-product-area pt-65 pb-50 related-product-border">
    <style>
        .product-thumb-fixed {
            height: 250px;
            width: 100%;
            display: flex;
            align-items: center;
            /* vertical center */
            justify-content: center;
            /* horizontal center */
            overflow: hidden;

            background: #f9f9f9;
            text-align: center;
            /* fallback for inline images */
        }

        .product-thumb-fixed img {
            max-height: 100%;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            aspect-ratio: 3/2;
            /* keeps aspect ratio */
            display: block;
            margin: 0 auto;
            /* centers horizontally */
            mix-blend-mode: multiply;
            /* optional */
        }
    </style>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="tpsection mb-40">
                    <h4 class="tpsection__title">Related Products</h4>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="tprelated__arrow d-flex align-items-center justify-content-end mb-40">
                    <div class="tprelated__prv"><i class="far fa-long-arrow-left"></i></div>
                    <div class="tprelated__nxt"><i class="far fa-long-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <div class="swiper-container related-product-active">
            <div class="swiper-wrapper">
                @foreach ($related as $article)
                    <div class="swiper-slide">
                        <div class="tpproduct pb-15 mb-30">
                            <div class="product-thumb-fixed">
                                <a href="{{ route('articles.details', ['slug' => $article['slug']]) }}">
                                    <img src="{{ route('media', ['img' => setMedia($article['thumbnail'])]) }}"
                                        alt="product-thumb">
                                </a>
                            </div>
                            <div class="tpproduct__content text-center">
                                <b>{{$article['brand_name']}}</b>
                                <h3 class="tpproduct__title text-center"><a
                                        href="{{route('articles.details', ['slug' => $article['slug']])}}">{{$article['name']}}</a>
                                </h3>
                                <div class="tpproduct__priceinfo p-relative">
                                    <div class="tpproduct__priceinfo-list text-primary">
                                        <b>{!! formatePrice($article['price'], 'oman', true)!!}</b>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                @endforeach
            </div>
        </div>
    </div>
</div>