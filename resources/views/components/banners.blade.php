<section class="slider-area">
    <div class="">
        {{-- Spinner (shown while loading) --}}
        <div id="banners-loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading banners...</span>
            </div>
        </div>

        {{-- Empty/Error --}}
        {{-- <div id="banners-empty" class="text-center text-muted py-5 d-none">
            No banners available right now.
        </div> --}}

        {{-- Desktop / Web Banners --}}
        <div id="banners-web" class="secondary-slider p-relative d-none d-sm-block">
            <div class="swiper-container webslider-active">
                <div class="swiper-wrapper"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        {{-- Mobile Banners --}}
        <div id="banners-mobile" class="secondary-slider p-relative d-block d-sm-none">
            <div class="swiper-container mobileslider-active">
                <div class="swiper-wrapper"></div>
                <div class="swiper-pagination text-danger"></div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(function () {
    const bannersGetUrl = route('banners/get'); // API route
    const $loading = $('#banners-loading');
    const $empty = $('#banners-empty');
    const $webWrap = $('#banners-web .swiper-wrapper');
    const $mobWrap = $('#banners-mobile .swiper-wrapper');

    $.ajax({
        url: bannersGetUrl,
        method: 'GET',
        success: function (res) {
            $loading.addClass('d-none');
            $webWrap.empty();
            $mobWrap.empty();

            if (!res || res.error || !Array.isArray(res.data) || res.data.length === 0) {
                $empty.removeClass('d-none');
                return;
            }
            $empty.addClass('d-none');

            // Append banners
            res.data.forEach(function (banner) {

                // === Web Banner ===
                if (banner.web_path || banner.web_html) {
                    $webWrap.append(`
                        <a class="swiper-slide" href="${banner.redirect_to || '#'}">
                            ${banner.web_html ? `<div class="banner-html">${banner.web_html}</div>` : ''}
                            ${banner.web_path ? `<img src="${route('media/' + setMedia(banner.web_path))}" alt="Banner" style="object-fit:cover;width:100%;">` : ''}
                        </a>
                    `);
                }

                // === Mobile Banner ===
                if (banner.mobile_path || banner.mobile_html) {
                    $mobWrap.append(`
                        <a class="swiper-slide" href="${banner.redirect_to || '#'}">
                            ${banner.mobile_html ? `<div class="banner-html">${banner.mobile_html}</div>` : ''}
                            ${banner.mobile_path ? `<img src="${route('media/' + setMedia(banner.mobile_path))}" alt="Banner" style="object-fit:cover;width:100%;">` : ''}
                        </a>
                    `);
                }
            });

            // === Initialize Swipers ===
            new Swiper('.webslider-active', {
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                autoplay: { delay: 4000 },
            });
            new Swiper('.mobileslider-active', {
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                autoplay: { delay: 4000 },
            });
        },
        error: function () {
            $loading.addClass('d-none');
            $empty.removeClass('d-none');
        }
    });
});
</script>

@endpush