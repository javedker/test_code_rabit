@extends('layout.master')

@section('title', 'New Password')

@section('content')
    <section class="track-area pt-80 pb-40">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="tptrack__product mb-40">
                        <div class="tptrack__content grey-bg-3">
                            <div class="tptrack__item d-flex mb-20">
                                <div class="tptrack__item-icon d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('img/icon/lock.png') }}" alt="" width="15">
                                </div>
                                <div class="tptrack__item-content">
                                    <h4 class="tptrack__item-title">New Password</h4>
                                    <p class="mt-2 text-muted fs-6">
                                        Create a strong new password to secure your account and regain access.
                                    </p>
                                </div>
                            </div>
                            <form action="" method="post" id="new-password-form">
                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-envelope"></i></span>
                                        <input type="email" id="email" name="email" placeholder="Enter New Password"
                                            value="{{$email}} " readonly>
                                    </div>

                                    <input type="hidden" name="token" value="{{$token}}">
                                </div>
                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-lock"></i></span>
                                        <input type="password" id="password" name="password"
                                            placeholder="Enter New Password">
                                    </div>
                                    <small id="password-error" class="text-danger"></small>
                                </div>
                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-lock"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            placeholder="Enter Confirm Password">
                                    </div>
                                    <small id="password-confirm-error" class="text-danger"></small>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-15">
                                    <div class="mx-2">
                                        <input type="checkbox" id="togglePassword">
                                        <label for="togglePassword" style="font-size: .85rem;">Show Passwords</label>
                                    </div>
                                    <div class="form-check mb-15">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#passwordPolicyModal"
                                            style="font-size:.8rem">View Policy</a>
                                    </div>
                                </div>



                                <div class="tptrack__btn" id="sign-up-submit">
                                    <button class="tptrack__submition">
                                        Reset Password <i class="fal fa-long-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="tptrack__btn" id="sign-up-spinner" style="display: none;">
                                    <button class="tptrack__submition" disabled
                                        style="pointer-events: none; opacity: 0.6; cursor: not-allowed;">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Please Wait... <i class="fal fa-long-arrow-right"></i>
                                    </button>
                                </div>

                              
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-password-privacy-policy />
@endsection


@push('scripts')
    <script src="{{asset('js/auth.js')}}"></script>
@endpush