@extends('layout.master')

@section('title', 'Register')

@section('content')
    <section class="track-area pt-80 pb-40">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="tptrack__product mb-40">
                        <div class="tptrack__content grey-bg-3 p-4">
                            {{-- Header --}}
                            <div class="tptrack__item d-flex mb-20">
                                <div class="tptrack__item-icon d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('img/icon/lock.png') }}" alt="" width="15">
                                </div>
                                <div class="tptrack__item-content">
                                    <h4 class="tptrack__item-title mt-3 text-uppercase">Register</h4>
                                    {{-- <p class="mt-2 text-muted fs-6">
                                        Your perfect climate is just a click away. Create an account to get started.
                                    </p> --}}
                                </div>
                            </div>
                            @if (session('existingUser'))
                                <div class="alert alert-info">
                                    {{-- We found your details,You are an Existing Customer with
                                    {{session('existingUser')['sap_number']}} --}}
                                    Good to see you again! This mobile no. is already registered with us under Name:
                                    {{ session('existingUser')['name'] }}, Customer Code
                                    {{ session('existingUser')['sap_number'] }}
                                </div>
                            @endif
                            {{-- Form --}}
                            <form action="#" method="POST" id="sign-up-form">


                                {{-- Title --}}
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <select name="title" class="tptrack__custom_select" required
                                        @if (session('existingUser') && session('existingUser')['title']) disabled @endif>

                                        <option value="">Select Title</option>
                                        <option value="mr" @if (session('existingUser') &&
                                                in_array(session('existingUser')['title'], [
                                                    'Mr',
                                                    'MR',
                                                    'mr',
                                                    'mR',
                                                    'Mr.',
                                                    'MR.',
                                                    'mr.',
                                                    'mR.',
                                                    'Mister',
                                                    'MISTER',
                                                    'mister',
                                                    'Mr ',
                                                    'MR ',
                                                    'mr ',
                                                    'Mr. ',
                                                    'MR. ',
                                                    'mr. ',
                                                ])) selected @endif>
                                            MR.
                                        </option>

                                        <option value="ms" @if (session('existingUser') &&
                                                in_array(session('existingUser')['title'], [
                                                    'Ms',
                                                    'MS',
                                                    'ms',
                                                    'mS',
                                                    'Ms.',
                                                    'MS.',
                                                    'ms.',
                                                    'mS.',
                                                    'Miss',
                                                    'MISS',
                                                    'miss',
                                                    'Ms ',
                                                    'MS ',
                                                    'ms ',
                                                    'Ms. ',
                                                    'MS. ',
                                                    'ms. ',
                                                ])) selected @endif>
                                            MS.
                                        </option>
                                    </select>

                                    {{-- Hidden input ensures value is still submitted when disabled --}}
                                    @if (session('existingUser') && !empty(session('existingUser')['title']))
                                        <input type="hidden" name="title"
                                            value="{{ strtolower(str_replace('.', '', session('existingUser')['title'])) }}">
                                    @endif
                                </div>

                                {{-- Full Name --}}
                                <div class="mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="tptrack__custom_input"
                                        @if (session('existingUser') && !empty(session('existingUser')['name'])) value="{{ session('existingUser')['name'] }}" readonly @endif
                                        placeholder="Enter your name" required />
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="tptrack__custom_input"
                                        @if (session('existingUser') && !empty(session('existingUser')['email'])) value="{{ session('existingUser')['email'] }}" readonly @endif
                                        placeholder="Enter your email" required />
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Password <span class="text-danger">*</span>
                                        <a href="#" class="text-primary" data-bs-toggle="modal"
                                            data-bs-target="#passwordPolicyModal" style="font-size:.8rem">
                                            View Policy
                                        </a>
                                    </label>
                                    <input type="password" name="password" id="password" class="tptrack__custom_input"
                                        placeholder="Enter your password" required />
                                    <span class="text-danger" id="password-error"></span>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="tptrack__custom_input" placeholder="Confirm your password" required />
                                    <span class="text-danger" id="password-confirm-error"></span>
                                </div>

                                {{-- Show Password --}}
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="togglePassword" />
                                        <label class="form-check-label" for="togglePassword">Show Password</label>
                                    </div>
                                </div>

                                {{-- Preferred Contact Methods --}}
                                <div class="mb-3">
                                    <label class="form-label">Contact Preference :</label>
                                    <div>
                                        @foreach (['Email', 'SMS', 'WhatsApp'] as $method)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="contact_method[]"
                                                    value="{{ $method }}" id="method-{{ $method }}" />
                                                <label class="form-check-label"
                                                    for="method-{{ $method }}">{{ $method }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Accept Terms --}}
                                <div class="mt-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input text-danger" required type="checkbox" value="yes"
                                            id="Conditions" name="is_terms_and_condition"
                                            oninvalid="this.setCustomValidity('Please agree to the terms and conditions')"
                                            onchange="this.setCustomValidity('');">
                                        <label class="form-check-label" for="Conditions">
                                            I have read and agree to the <a href="{{ route('home.terms') }}"
                                                class="text-primary" target="_blank">Terms & Conditions <span
                                                    class="text-danger">*</span> </a>
                                        </label>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="tptrack__btn" id="sign-up-submit">
                                    <button type="submit" class="tptrack__submition">
                                        Register Now <i class="fal fa-long-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="tptrack__btn" id="sign-up-spinner" style="display:none;">
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

    {{-- Password Policy Modal --}}
    <x-password-privacy-policy />
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush
