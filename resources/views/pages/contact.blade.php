@extends('layout.master')

@section('title', 'Contact Us')

@section('content')
    <!-- contact-area-start -->
    <section class="contact-area pt-80 pb-80">
        <div class="container">
            <div class="row">
                <!-- Left: Contact info -->
                <div class="col-lg-4 col-12">
                    <div class="tpcontact__right mb-40">
                        <div class="tpcontact__shop mb-30">
                            <h4 class="tpshop__title mb-25">Get In Touch</h4>
                            <div class="tpshop__info">
                                <ul>
                                    <li>
                                        <i class="fal fa-map-marker-alt"></i>
                                        <a href="https://maps.app.goo.gl/t7k96q4VsJGbEi1G6" target="_blank">
                                            {{config('app.name')}},
                                            KR Infra Office, Ghala
                                        </a>

                                    </li>
                                    <li>
                                        <i class="fal fa-envelope"></i>
                                        <a href="mailto:{{config('app.support_email')}}">{{config('app.support_email')}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tpcontact__support">
                            <a href="tel:+968{{config('app.support_contact')}}">
                                Get Support On Call <i class="fal fa-phone"></i>
                            </a>
                            <a href="https://maps.app.goo.gl/t7k96q4VsJGbEi1G6" target="_blank" rel="noopener">
                                Get Direction <i class="fal fa-map-marker-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact form -->
                <div class="col-lg-8 col-12">
                    <div class="map-area">
                        <div class="tpshop__location-map">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2914.1267624164398!2d58.33612762423552!3d23.57047662879357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e8e00455817f8d1%3A0x72d6d22939e12e09!2sKR%20Infra!5e1!3m2!1sen!2som!4v1755084733665!5m2!1sen!2som"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-area-end -->

    <!-- map-area-start -->
   
    <!-- map-area-end -->
@endsection