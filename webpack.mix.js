const mix = require('laravel-mix');

// Combine and minify CSS
mix.styles([
    'public/assets/css/bootstrap.min.css',
    'public/assets/css/animate.css',
    'public/assets/css/swiper-bundle.css',
    'public/assets/css/slick.css',
    'public/assets/css/nice-select.css',
    'public/assets/css/fontawesome.min.css',
    'public/assets/css/magnific-popup.css',
    'public/assets/css/spacing.css',
    'public/assets/css/meanmenu.css',
    'public/assets/css/main.css',
], 'public/assets/css/minified.min.css');

// Combine and minify JS
mix.scripts([
    'public/assets/js/jquery.js',
    'public/assets/js/waypoints.js',
    'public/assets/js/bootstrap.bundle.min.js',
    'public/assets/js/swiper-bundle.js',
    'public/assets/js/slick.js',
    'public/assets/js/magnific-popup.js',
    'public/assets/js/nice-select.js',
    'public/assets/js/counterup.js',
    'public/assets/js/wow.js',
    'public/assets/js/isotope-pkgd.js',
    'public/assets/js/imagesloaded-pkgd.js',
    'public/assets/js/countdown.js',
    'public/assets/js/ajax-form.js',
    'public/assets/js/meanmenu.js',
    'public/assets/js/main.js',
], 'public/assets/js/minified.min.js');

// Optional: versioning for cache busting
mix.version();
