@extends('layout.master')

@section('title', 'Mobile')

@push('styles')
    <style>
        .otp-wrapper {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin: 20px 0;
        }

        .otp-input {
            width: 40px;
            font-size: 28px;
            text-align: center;
            border: none;
            border-bottom: 2px solid #ccc;
            background: transparent;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .otp-input:focus {
            border-bottom: 2px solid #ff4d4d;
        }
    </style>
@endpush

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
                                <div class="tptrack__item-content">
                                    <h4 class="tptrack__item-title">Please enter the One-Time Password to verify your
                                        mobile number</h4>
                                    <p>A One-Time Password has been sent to +968
                                        {{-- {{ session('mobile') ? '****' . substr(session('mobile'), -4) : '****' }} --}}
                                        {{session('mobile')}}
                                    </p>
                                </div>
                            </div>
                            <form action="" method="post" id="verify-form">
                                <div class="otp-wrapper mb-10 d-flex justify-content-between" id="otp-inputs">
                                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                                        inputmode="numeric">
                                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                                        inputmode="numeric">
                                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                                        inputmode="numeric">
                                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                                        inputmode="numeric">
                                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                                        inputmode="numeric">
                                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                                        inputmode="numeric">
                                </div>
                                <input type="hidden" name="otp" id="fullOtp">

                                <div class="tptrack__btn" id="sign-up-submit">
                                    <button class="tptrack__submition">
                                        Verify Now<i class="fal fa-long-arrow-right"></i></button>
                                </div>
                                <div class="tptrack__btn" id="sign-up-spinner" style="display: none;">
                                    <button class="tptrack__submition" disabled
                                        style="pointer-events: none; opacity: 0.6; cursor: not-allowed;">
                                        <span class="spinner-border spinner-border-sm" id="loginSpinner" role="status"
                                            aria-hidden="true"></span>
                                        Please Wait... <i class="fal fa-long-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="tpsign__account mt-3">
                                    <span>Didn't receive the OTP? <a href="" id="resend-otp"
                                            data-mobile="{{session('mobile')}}">Resend</a>
                                        <span id="resend-otp-spinner" class="spinner-border spinner-border-sm text-primary"
                                            style="display: none;" role="status" aria-hidden="true"></span>
                                    </span>
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
    <script>
        $(document).ready(function () {
            const $inputs = $('.otp-input');

            $inputs.on('input', function () {
                const $this = $(this);
                const value = $this.val().replace(/\D/g, ''); // Only numbers
                $this.val(value); // remove non-digit

                if (value.length === 1) {
                    $this.next('.otp-input').focus();
                }

                updateHiddenOtp();
            });

            $inputs.on('keydown', function (e) {
                const $this = $(this);

                if (e.key === 'Backspace' && !$this.val()) {
                    $this.prev('.otp-input').focus().val('');
                }
            });

            $inputs.on('paste', function (e) {
                const pastedData = e.originalEvent.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                const digits = pastedData.split('');
                $inputs.each(function (i) {
                    $(this).val(digits[i] || '');
                });
                updateHiddenOtp();
                e.preventDefault();
            });

            function updateHiddenOtp() {
                let fullOtp = '';
                $inputs.each(function () {
                    fullOtp += $(this).val();
                });
                $('#fullOtp').val(fullOtp);
            }
        });
    </script>
@endpush