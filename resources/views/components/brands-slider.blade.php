<section class="brands-slider container pt-50" style="overflow-x: hidden;">
    <div class="row align-items-center mb-3">
        <div class="col text-center">
            <h3 class="mb-0 text-uppercase mx-3">Shop by Brands</h3>
        </div>
    </div>

    @php
        $brands = [
            [
                'name' => 'Electrolux',
                'logo' => asset(path: 'img/brand/electrolux.png'),
                'slug' => 'electrolux',
            ],
            [
                'name' => 'GENERAL',
                'logo' => asset('img/brand/general.jpg'),
                'slug' => 'GENERAL',
            ],
            [
                'name' => 'kelon',
                'logo' => asset('img/brand/kelon.png'),
                'slug' => 'kelon',
            ],
            [
                'name' => 'la germania',
                'logo' => asset('img/brand/la-germania.jpg'),
                'slug' => 'la-germania',
            ],
            [
                'name' => 'skm',
                'logo' => asset('img/brand/skm.png'),
                'slug' => 'skm',
            ],
            [
                'name' => 'simfer',
                'logo' => asset('img/brand/simfer.png'),
                'slug' => 'simfer',
            ],
        ];
    @endphp

    <div class="swiper brands-swiper" style="width: 100%; overflow: hidden;">
        <div class="swiper-wrapper mb-3" style="display: flex; flex-wrap: nowrap;">
            @foreach ($brands as $brand)
                <div class="swiper-slide text-center" style="flex-shrink: 0; width: auto; max-width: 150px;">
                    <a href="{{ route('articles', ['brand' => strtoupper($brand['name'])]) }}" class="brand-card">
                        <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }} logo" class="brand-logo" style="max-width: 100%; height: auto;">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
