@extends('layout.master')
@section('title', 'Security')

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
                                    <h4 class="tptrack__item-title text-uppercase mt-3">Change Password</h4>
                                    {{-- <p class="mt-2 text-muted fs-6">
                                        Once you change your password, you’ll be signed out and will need to sign in again.
                                    </p> --}}
                                </div>
                            </div>

                            {{-- Inline reset/change password form (for logged-in user) --}}
                            <form action="{{ route('account.reset-password') }}" method="post" id="resetPwdForm">
                                @csrf

                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-lock"></i></span>
                                        <input type="password" name="current_password" placeholder="Enter Current Password"
                                            required>
                                    </div>
                                    <small class="text-danger" id="current-password-error"></small>
                                </div>

                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-lock"></i></span>
                                        <input type="password" id="password" name="password"
                                            placeholder="Enter New Password" minlength="8" required>
                                    </div>
                                    <small class="text-danger" id="password-error"></small>
                                </div>

                                <div class="tptrack__id mb-10">
                                    <div class="tptrack-wrapper">
                                        <span><i class="fal fa-lock"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            placeholder="Enter Confirm Password" minlength="8" required>
                                    </div>
                                    <small class="text-danger" id="password-confirm-error"></small>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-15">
                                    <div class="mx-2">
                                        <input type="checkbox" id="togglePassword">
                                        <label for="togglePassword" style="font-size:18px;">Show Passwords</label>
                                    </div>
                                    <div class="form-check mb-15">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#passwordPolicyModal"
                                            style="font-size:18px;">
                                            View Password Policy
                                        </a>
                                    </div>
                                </div>

                                <div class="tptrack__btn">
                                    <button id="reset-pwd-submit" class="tptrack__submition" type="submit">
                                        Reset Password <i class="fal fa-long-arrow-right"></i>
                                    </button>
                                    <button id="reset-pwd-spinner" class="tptrack__submition" type="button" disabled
                                        style="display:none; pointer-events:none; opacity:.6;">
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

    {{-- Password policy modal --}}
    <x-password-privacy-policy />
@endsection

@push('scripts')
    <script src="{{ asset('js/security.js') }}"></script>
    <script>
        // simple "Show Passwords" toggle
        document.getElementById('togglePassword')?.addEventListener('change', function () {
            const type = this.checked ? 'text' : 'password';
            ['current_password', 'password', 'password_confirmation'].forEach(id => {
                const el = document.querySelector(`[name="${id}"]`) || document.getElementById(id);
                if (el) el.type = type;
            });
        });
    </script>
@endpush