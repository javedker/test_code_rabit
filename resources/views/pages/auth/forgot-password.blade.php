@extends('layout.master')

@section('title', 'Forgot Password')

@section('content')
    <section class="track-area pt-80 pb-40">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="tptrack__product mb-40">
                        <div class="tptrack__content grey-bg-3">
                            <div class="tptrack__item d-flex mb-20">
                                <div class="tptrack__item-icon d-flex justify-content-center align-items-center">
                                    <img src="{{asset('img/icon/lock.png')}}" alt="" width="15">
                                </div>
                                <div class="tptrack__item-content mt-3">
                                    <h4 class="tptrack__item-title text-uppercase">Forgot Password</h4>

                                </div>
                            </div>
                            <form action="" method="post" id="forgot-password-form">
                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-envelope"></i></span>
                                        <input type="email" name="email" placeholder="Enter your email address">
                                    </div>
                                </div>

                                <div class="tptrack__btn" id="sign-up-submit">
                                    <button class="tptrack__submition">
                                        Send Password Reset Link<i class="fal fa-long-arrow-right"></i></button>
                                </div>
                                <div class="tptrack__btn" id="sign-up-spinner" style="display: none;">
                                    <button class="tptrack__submition" disabled
                                        style="pointer-events: none; opacity: 0.6; cursor: not-allowed;">
                                        <span class="spinner-border spinner-border-sm" id="loginSpinner" role="status"
                                            aria-hidden="true"></span>
                                        Please Wait... <i class="fal fa-long-arrow-right"></i>
                                    </button>
                                </div>


                                <div class="tpsign__account mt-2">
                                    Don't have an account? <a href="{{route('auth.mobile')}}"
                                        class="text-decoration-underline">Register</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('js/auth.js')}}"></script>
@endpush