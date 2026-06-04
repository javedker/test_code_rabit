<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Souqool - Buy Home Appliances & Air Conditioners Online in Oman</title>



    {{-- Favicon --}}

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('img/logo/favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('img/logo/favicon-64x64.png') }}">

    <!-- Apple Touch -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo/favicon-180x180.png') }}">

    <!-- Android/Chrome -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/logo/favicon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="256x256" href="{{ asset('img/logo/favicon-256x256.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('img/logo/favicon-512x512.png') }}">

    <!-- Windows tiles -->
    <meta name="msapplication-TileColor" content="#00426a">
    <meta name="theme-color" content="#00426a">


    {{-- CSRF TOKEN --}}
    <meta name="_token" content="{{ csrf_token() }}">


    {{-- Meta Keywords and Description --}}

    <meta name="description"
        content="Shop premium home appliances and air conditioners at Souqool.com. Explore top brands like Electrolux, General, SKM, and more. Fast delivery across Oman.">
    <meta name="keywords"
        content="Souqool, buy appliances online Oman, home appliances Oman, air conditioners Oman, refrigerators Oman, washing machines Oman, kitchen appliances Oman">


    {{-- Graph and Twitter Card --}}
    <meta property="og:title" content="Souqool - Online Marketplace for Home Appliances in Oman">
    <meta property="og:description"
        content="Discover a wide range of appliances and AC units from top brands. Shop online at Souqool.com with fast delivery in Oman.">
    <meta property="og:image" content="https://souqool.com/img/logo/logo.png">
    <meta property="og:url" content="https://souqool.com">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Souqool - Buy Home Appliances in Oman">
    <meta name="twitter:description" content="Premium marketplace for appliances & air conditioners in Oman.">
    <meta name="twitter:image" content="https://souqool.com/img/logo/logo.png">

    {{-- Styles --}}
    @include('layout.styles')

    {{-- Custom Style --}}
    @stack('styles')

    {{-- Google Analytics --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ config('app.google_analytics_id') }}');
    </script>

</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Pre Loader --}}
    <div id="preloader">
        <div class="cg-container" id="fog-loader">
            <div class="vanta-circle"></div>
            <p class="loader-text"></p>
        </div>

    </div>


    <!-- Scroll-top -->
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="fas fa-angle-up"></i>
    </button>
    <!-- Scroll-top-end-->

    {{-- Header --}}
    <x-layout.header />
    <x-layout.header-mobile />
    <x-layout.header-cart />
    <x-account />

    {{-- Main Content --}}
    <main class="flex-grow-1">
        <div id="main-content" class="">
            @yield('content')
        </div>
        <div id="main-spinner" class="d-flex justify-content-center align-items-center d-none"
            style="min-height: 50vh;">

            <div class="spinner-border text-primary" role="status" style="width: 6rem; height: 6rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <x-layout.footer />
    <x-toast />
</body>

<script src="{{ asset('js/fog.min.js') }}"></script>
<script src="{{ asset('js/three.min.js') }}"></script>
@include('layout.scripts')
{{-- Custom Scripts --}}
@stack('scripts')

{{-- Custom Hidden Inputs --}}
<input type="hidden" name="" id="app-url" value="{{ config('app.url') }}">


{{-- Flash Sessions --}}

@if (session()->has('error'))
    <script>
        toast.error("{{ session('error') }}")
    </script>
@endif
@if (session()->has('success'))
    <script>
        toast.error("{{ session('success') }}")
    </script>
@endif

</html>
