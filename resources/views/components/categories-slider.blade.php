<section class="cat-slider container pt-45 swiper-container platinam-pro-active">
    <div class="row align-items-center mb-3">
        <div class="col text-center">
            <h3 class="mb-0 text-uppercase mx-3">Shop by Category</h3>
        </div>
        <div class="col-auto">
            <!-- Same arrow style as Popular Products -->
            <div class="tpplatiarrow d-flex align-items-center justify-content-end">
                <div class="cat-prev me-2"><i class="far fa-chevron-left"></i></div>
                <div class="cat-next"><i class="far fa-chevron-right"></i></div>
            </div>
        </div>
    </div>


    @php
        $categories = [
            ['file' => 'ac.png', 'title' => 'Air Conditioners', 'filter' => 'category', 'value' => 'AC Window,AC Split'],
            ['file' => 'fridge.png', 'title' => 'Refrigerators', 'filter' => 'category', 'value' => 'Refrigerators'],
            ['file' => 'hob.png', 'title' => 'Hobs', 'filter' => 'category', 'value' => 'Hobs'],
            ['file' => 'hood.png', 'title' => 'Hoods', 'filter' => 'category', 'value' => 'Hoods'],
            ['file' => 'oven.png', 'title' => 'Ovens', 'filter' => 'category', 'value' => 'Ovens'],
            ['file' => 'vacuum.png', 'title' => 'Vacuum Cleaners', 'filter' => 'category', 'value' => 'Vacuum Cleaners'],
            ['file' => 'washing-machine.png', 'title' => 'Washing Machines', 'filter' => 'category', 'value' => 'Washing Machines'],
        ]
        ;
        usort($categories, fn($a, $b) => strcmp($a['file'], $b['file']));
      @endphp

    <div class="swiper cat-swiper">
        <div class="swiper-wrapper">
            @foreach ($categories as $cat)
                <div class="swiper-slide">
                    <a href="{{ route('articles', [$cat['filter'] => $cat['value']]) }}" class="cat-card">
                        <span class="cat-circle">
                            <img src="{{ asset('img/categories/' . $cat['file']) }}" alt="{{ $cat['title'] }}">
                        </span>
                        <span class="cat-title">{{ $cat['title'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>