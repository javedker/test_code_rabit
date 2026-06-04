<footer>
    <div class="footer-area theme-bg green-footer pt-65">
        <div class="container">
            <div class="main-footer pb-15">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="footer-widget footer-col-1 mb-40">
                            <div class="footer-logo mb-30">
                                <a href="{{route('home')}}"><img src="{{asset('img/logo/logo.png')}}" class="logo-img"
                                        alt="logo"></a>
                                {{-- <div class="footer-content mx-3">
                                    <p>Smart Living Starts Here.</p>
                                </div> --}}
                                <div class="footer-cta__contact mt-2">
                                    <div class="footer-cta__icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="footer-cta__text mt-1">
                                        <a href="tel:72443400">+968 72443400
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="footer-widget footer-col-2 ml-30 mb-40">
                            <h4 class="footer-widget__title mb-30">Information</h4>
                            <div class="footer-widget__links">
                                <ul>
                                    <li><a href="{{route('home.about')}}">About Us</a></li>
                                    <li><a href="{{route('home.contact')}}">Contact Us</a></li>
                                    <li><a href="{{route('home.faqs')}}">FAQs</a></li>
                                    <li><a href="{{route('home.faqs',['q'=>'How can I contact for service-related queries?'])}}">Service</a></li>
                                    <li><a href="{{route('home.terms')}}">Terms & Condition</a></li>
                                    <li><a href="{{route('home.privacy')}}">Privacy Policy</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="footer-widget footer-col-3 mb-40">
                            <h4 class="footer-widget__title mb-30">My Account</h4>
                            <div class="footer-widget__links">
                                <ul>
                                    <li><a href="{{route('account.orders')}}">Order Information</a></li>
                                    <li><a href="{{route('home.terms')}}">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="footer-widget footer-col-4 mb-40">
                            <h4 class="footer-widget__title mb-30">Social Network</h4>
                            <div class="footer-widget__links">
                                <ul>
                                    <li><a href="https://www.linkedin.com/company/khimjiramdas-infrastructure
" target="_blank"><i class="fab fa-linkedin"></i>Linkedin</a></li>
                                    <li><a href="https://www.instagram.com/kraircon.appliances" target="_blank"><i
                                                class="fab fa-instagram"></i>Instagram</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="footer-widget footer-col-5 mb-40">
                            <h4 class="footer-widget__title mb-30">Payment Methods</h4>
                            <div class="footer-widget__links">
                                <ul class="d-flex gap-3 flex-wrap">
                                    <li>
                                        <img src="{{asset('img/payment-methods/apple-pay.svg')}}" alt="Visa" width="40"
                                            height="25">
                                    </li>
                                    <li>
                                        <img src="{{asset('img/payment-methods/master.jpg')}}" alt="Mastercard"
                                            width="40" height="25">
                                    </li>
                                    <li>
                                        <img src="{{asset('img/payment-methods/visa.jpg')}}" alt="Apple Pay" width="45"
                                            height="25">
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="row justify-content-between">
                    <div class="col-6 offset-3 text-center">
                        <div class="footer-copyright__content">
                            <span>Copyright {{date('Y')}}, All rights reserved. Powered
                                by KR Digital.</span>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</footer>