@php
    $fullName = signedUser('name');
    $nameParts = explode(' ', trim($fullName));

    // First and last initials
    $firstInitial = strtoupper(substr($nameParts[0], 0, 1));
    $lastInitial = isset($nameParts[1]) ? strtoupper(substr(end($nameParts), 0, 1)) : '';
    $nameInitials = $firstInitial . $lastInitial;

    // First name only
    $firstName = ucfirst($nameParts[0]);
@endphp

<header>
    {{-- <x-top-header /> --}}
    <div class="logo-area green-logo-area  d-none d-xl-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-3">
                    <div
                        class="header-logo d-flex align-items-center justify-content-center justify-content-md-start py-2">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('img/logo/logo.png') }}" alt="logo" class="logo-img" />
                        </a>
                    </div>

                </div>
                <div class="col-xl-10 col-lg-9">
                    <div class="header-meta-info d-flex align-items-center justify-content-between">
                        <div class="header-search-bar">
                            <form action="#">
                                <div class="search-info p-relative" style="position: relative;">
                                    <button class="header-search-icon">
                                        <i class="fal fa-search"></i>
                                    </button>
                                    <input type="text" class="header-search-main" id="search-input"
                                        placeholder="Search products..." autocomplete="off">

                                    <!-- Suggestion Dropdown -->
                                    <div id="search-suggestions" class="search-suggestions">
                                        <!-- JS will populate this list -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="header-meta header-language d-flex align-items-center">
                            {{-- <div class="header-meta__lang">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <img src="assets/img/icon/lang-flag.png" alt="flag">
                                            English
                                            <span><i class="fal fa-angle-down"></i></span>
                                        </a>
                                        <ul class="header-meta__lang-submenu">
                                            <li>
                                                <a href="#">Arabic</a>
                                            </li>
                                            <li>
                                                <a href="#">Spanish</a>
                                            </li>
                                            <li>
                                                <a href="#">Mandarin</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div> --}}

                            <div class="header-meta__social d-flex align-items-center ml-25">

                                @if (isUserSignedIn())
                                    <a href="#" class="account-link" data-bs-toggle="offcanvas"
                                        data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
                                        <span class="account-icon initials-circle">{{ $nameInitials }}</span>
                                    </a>
                                @else
                                    <a href="{{ route('auth.sign-in') }}" class="account-link">
                                        <span class="account-icon"><i class="fal fa-user"></i></span>
                                    </a>
                                @endif
                                <div class="d-flex align-items-center mx-2">
                                    <button class="header-cart p-relative tp-cart-toggle header-cart-button mx-2"
                                        title="Cart">
                                        <i class="fal fa-shopping-cart"></i>
                                        <span style="display: none" class="cart-count">0</span>
                                    </button>
                                    <a href="{{ route('favorites') }}" title="Favorites" class="mx-2">
                                        <i class="fal fa-heart"></i>
                                    </a>
                                    <a href="{{ route('compares') }}" title="Compares" class="mx-2">
                                        <i class="fal fa-exchange-alt"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-menu-area tertiary-main-menu mt-0 d-none d-xl-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-3">
                    <div class="cat-menu__category p-relative">
                        <a data-bs-toggle="collapse" href="#collapsecategory" role="button" aria-expanded="false"
                            aria-controls="collapsecategory"><i class="fal fa-bars"></i>Categories</a>

                        <div class="category-menu collapse" id="collapsecategory">
                            <ul class="cat-menu__list">
                                @foreach (getCategories() as $segment => $categories)
                                    <li class="menu-item-has-children">
                                        <a>
                                            {{ $segment }}
                                        </a>
                                        <ul class="submenu mb-10">
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a href="{{ route('articles', ['category' => $category]) }}">
                                                        {{ $category }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="col-xl-6 col-lg-8">
                    <div class="main-menu mx-3 ">
                        <nav id="mobile-menu" class="header-render-active-menu">
                            <ul>
                                <li class="has-dropdown">
                                    <a href="{{ route('articles') }}">Shop By Brands</a>
                                    <ul class="submenu">
                                        <li><a href="{{ route('articles', ['brand' => 'ELECTROLUX']) }}">Electrolux</a>
                                        </li>
                                        <li><a href="{{ route('articles', ['brand' => 'GENERAL']) }}">General</a></li>
                                        <li><a href="{{ route('articles', ['brand' => 'KELON']) }}">Kelon</a></li>
                                        <li><a href="{{ route('articles', ['brand' => 'LA GERMANIA']) }}">La
                                                Germania</a></li>
                                        <li><a href="{{ route('articles', ['brand' => 'SIMFER']) }}">Simfer</a></li>
                                        <li><a href="{{ route('articles', ['brand' => 'SKM']) }}">SKM</a></li>
                                    </ul>

                                </li>

                                <li><a href="{{ route('home') }}#new-arrivals">New Arrivals</a></li>
                                @if ($offerStatus)
                                    <li style="position: relative; display: inline-block;">
                                        <a class="offer-banner"
                                            href="{{ $offerRedirectUrl ? $offerRedirectUrl . '#special-offers' : route('home') . '#special-offers' }}">{{ $offerTitle }}</a>
                                        <span class="hot-badge">{{ $offerBadge }}</span>
                                    </li>
                                @endif
                                <li class="has-dropdown d-block d-sm-none">
                                    <a href="{{ route('articles') }}">Categories</a>
                                    <ul>
                                        @foreach (getCategories() as $segment => $categories)
                                            <li class="menu-item-has-children">
                                                <a>
                                                    {{ $segment }}
                                                </a>
                                                <ul class="submenu mb-10">
                                                    @foreach ($categories as $category)
                                                        <li>
                                                            <a
                                                                href="{{ route('articles', ['category' => $category]) }}">
                                                                {{ $category }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 text-end">
                    <div class="menu-contact">
                        <ul>
                            <li>
                                <div class="menu-contact__item">
                                    <div class="menu-contact__icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="menu-contact__info">
                                        <a href="tel:968{{ config('app.support_contact') }}">+968
                                            {{ config('app.support_contact') }}</a>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="menu-contact__item">
                                    <div class="menu-contact__icon">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <div class="menu-contact__info">
                                        <a
                                            href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-area-end -->

<!-- header-xl-sticky-area -->
<div id="header-sticky" class="logo-area tp-sticky-one mainmenu-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-2 col-lg-3">
                <div class="logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('img/logo/logo.png') }}" class="logo-img"
                            alt="logo"></a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="main-menu header-render-active-menu-sticky">
                    <ul>

                        <li class="has-dropdown">
                            <a href="{{ route('articles') }}">Shop By Brands</a>
                            <ul class="submenu">
                                <li><a href="{{ route('articles', ['brand' => 'ELECTROLUX']) }}">ELECTROLUX</a></li>
                                <li><a href="{{ route('articles', ['brand' => 'GENERAL']) }}">GENERAL</a></li>
                                <li><a href="{{ route('articles', ['brand' => 'KELON']) }}">KELON</a></li>
                                <li><a href="{{ route('articles', ['brand' => 'LA GERMANIA']) }}">LA GERMANIA</a></li>
                                <li><a href="{{ route('articles', ['brand' => 'SIMFER']) }}">SIMFER</a></li>
                                <li><a href="{{ route('articles', ['brand' => 'SKM']) }}">SKM</a></li>
                            </ul>

                        </li>

                        <li><a href="{{ route('home') }}#new-arrivals">New Arrivals</a></li>

                        @if ($offerStatus)
                            <li style="position: relative; display: inline-block;">
                                <a class="offer-banner"
                                    href="{{ $offerRedirectUrl ? $offerRedirectUrl . '#special-offers' : route('home') . '#special-offers ' }}">{{ $offerTitle }}</a>
                                <span class="hot-badge">{{ $offerBadge }}</span>
                            </li>
                        @endif
                    </ul>
                    </nav>
                </div>
            </div>
            <div class="col-xl-4 col-lg-9">
                <div class="header-meta-info d-flex align-items-center justify-content-end">
                    <div class="header-meta__social  d-flex align-items-center">
                        <div class="header-meta__search-5 ml-25">
                            <div class="header-search-bar-5">
                                <form action="#" style="position:relative;">
                                    <div class="search-info-5 p-relative">
                                        <button class="header-search-icon-5"><i class="fal fa-search"></i></button>
                                        <input type="text" class="header-search search-input-small"
                                            placeholder="Search products...">
                                    </div>
                                    <div class="search-suggestions-small"></div>
                                </form>
                            </div>
                        </div>
                        @if (isUserSignedIn())
                            <a href="#" class="account-link" data-bs-toggle="offcanvas"
                                data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
                                <span class="account-icon initials-circle">{{ $nameInitials }}</span>
                            </a>
                        @else
                            <a href="{{ route('auth.sign-in') }}" class="account-link">
                                <span class="account-icon"><i class="fal fa-user"></i></span>
                            </a>
                        @endif
                        <div class="d-flex align-items-center mx-2">
                            <button class="header-cart p-relative tp-cart-toggle header-cart-button mx-2"
                                title="Cart">
                                <i class="fal fa-shopping-cart"></i>
                                <span style="display: none" class="cart-count">0</span>
                            </button>
                            <a href="{{ route('favorites') }}" class="mx-2" title="Favorites">
                                <i class="fal fa-heart"></i>
                            </a>
                            <a href="{{ route('compares') }}" class="mx-2" title="Compares">
                                <i class="fal fa-exchange-alt"></i>
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-xl-sticky-end -->

<!-- header-md-lg-area -->
<div id="header-tab-sticky" class="tp-md-lg-header d-none d-md-block d-xl-none pt-30 pb-30">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 d-flex align-items-center">
                <div class="header-canvas flex-auto">
                    <button class="tp-menu-toggle"><i class="far fa-bars"></i></button>
                </div>
                <div class="logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('img/logo/logo.png') }}" class="logo-img"
                            alt="logo"></a>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="header-meta-info d-flex align-items-center justify-content-between">
                    <div class="header-search-bar">
                        <form action="#" class="search-form">
                            <div class="search-info position-relative">
                                <button type="button" class="header-search-icon">
                                    <i class="fal fa-search"></i>
                                </button>

                                <input type="text" class="header-search search-input-small"
                                    placeholder="Search products..." />

                                <!-- ⬇️ move this INSIDE .search-info -->
                                <div class="search-suggestions-small"></div>
                            </div>
                        </form>

                    </div>
                    <div class="header-meta__social d-flex align-items-center ml-25">
                        <button class="header-cart p-relative tp-cart-toggle header-cart-button">
                            <i class="fal fa-shopping-cart"></i>
                            <span style="display: none" class="cart-count">0</span>
                        </button>
                        @if (isUserSignedIn())
                            <a href="#" class="account-link" data-bs-toggle="offcanvas"
                                data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
                                <span class="account-icon initials-circle">{{ $nameInitials }}</span>
                            </a>
                        @else
                            <a href="{{ route('auth.sign-in') }}" class="account-link">
                                <span class="account-icon"><i class="fal fa-user"></i></span>
                            </a>
                        @endif
                        <div class="d-flex align-items-center mx-2">
                            <button class="header-cart p-relatisve tp-cart-toggle header-cart-button mx-2"
                                title="Cart">
                                <i class="fal fa-shopping-cart"></i>
                                <span style="display: none" class="cart-count">0</span>
                            </button>
                            <a href="{{ route('favorites') }}" class="mx-2" title="Favorites">
                                <i class="fal fa-heart"></i>
                            </a>
                            <a href="{{ route('compares') }}" class="mx-2" title="Compares">
                                <i class="fal fa-exchange-alt"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="header-mob-sticky" class="tp-md-lg-header d-md-none pt-20 pb-20">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-3 d-flex align-items-center">
                <div class="header-canvas flex-auto">
                    <button class="tp-menu-toggle"><i class="far fa-bars"></i></button>
                </div>
            </div>
            <div class="col-6">
                <div class="logo text-center">
                    <a href="{{ route('home') }}"><img src="{{ asset('img/logo/logo.png') }}" class="logo-img"
                            alt="logo"></a>
                </div>
            </div>
            <div class="col-3">
                <div class="header-meta-info d-flex align-items-center justify-content-end ml-25">
                    <div class="header-meta m-0 d-flex align-items-center">
                        <div class="header-meta__social d-flex align-items-center">
                            <button class="header-cart p-relative tp-cart-toggle header-cart-button">
                                <i class="fal fa-shopping-cart"></i>
                                <span style="display: none" class="cart-count">0</span>
                            </button>
                            @if (isUserSignedIn())
                                <a href="#" class="account-link" data-bs-toggle="offcanvas"
                                    data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
                                    <span class="account-icon initials-circle">{{ $nameInitials }}</span>
                                </a>
                            @else
                                <a href="{{ route('auth.sign-in') }}" class="account-link">
                                    <span class="account-icon"><i class="fal fa-user"></i></span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-search-bar">
            <form action="#" style="position:relative;">
                <div class="search-info-5 p-relative">
                    <button type="button" class="header-search-icon">
                        <i class="fal fa-search"></i>
                    </button>

                    <input type="text" class="header-search search-input-small" placeholder="Search products...">
                </div>
                <div class="search-suggestions-small"></div>
            </form>

        </div>
    </div>
</div>
<!-- header-md-lg-area -->
