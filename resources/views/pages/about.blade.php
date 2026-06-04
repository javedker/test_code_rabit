@extends('layout.master')

@section('title', 'About Us')

@section('content')
    <!-- About Section -->
    <section class="about-area pt-30 pb-40">
        <div class="container">
          
            <div class="row">
                <div class="col-sm-12">
                    <div class="tpabout__inner-title-area">
                        <h4 class="tpabout__inner-title">About Us</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="tpabout__inner-story">
                        <p>
                            Offering the entire spectrum of HVAC air-conditioning solutions to a clientele that ranges
                            from residential villas to large commercial establishments and complexes, Khimji Ramdas Air
                            Conditioning has an air-conditioner product portfolio that includes General, Classic, Kelon,
                            Electrolux and SKM. In appliances the range includes Electrolux, La Germania, Frigidaire and
                            Simfer. These are retailed through over 100 channel partners as well as seven company-owned
                            showrooms across Oman. Efficient and quick after-sales support is ensured through Khimji
                            Ramdas independent service centre Siyana Wa Khidmaat, as well as a fleet of twenty fully
                            equipped mobile service vans.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Section -->
    <section class="feature-area  pb-10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="tpfeature__inner-thumb mb-70">
                        <img src="{{ asset('img/banner/about-banner-1.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="tpabout__inner-title-area">
                        <h4 class="tpabout__inner-title">Serving Families with Care</h4>
                        <p>
                            At Khimji Ramdas, we understand that a comfortable home is essential for family well-being.
                            Our range of air-conditioning and home appliances is specifically curated to enhance the
                            quality of life for families. From keeping your home cool during the scorching summer months
                            to providing reliable kitchen appliances for everyday use, we are dedicated to creating a
                            comfortable and convenient living environment for your family.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="tpabout__inner-title-area about-inner-content mr-100 mb-70">
                        <h4 class="tpabout__inner-title mb-25">Customer-Centric Approach</h4>
                        <p>
                            At Khimji Ramdas, customer satisfaction is at the heart of everything we do. We strive to
                            build long-term relationships with our clients by offering superior products, personalized
                            solutions, and exceptional support services. Your trust and satisfaction are our top
                            priorities.
                        </p>

                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="tpfeature__inner-thumb mb-70">
                        <img src="{{ asset('img/banner/about-banner-2.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection