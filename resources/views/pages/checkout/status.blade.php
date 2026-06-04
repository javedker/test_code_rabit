@extends('layout.master')

@section('title', 'Checkout')

@section(section: 'content')
<div class="container py-5 checkout-spinner">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="container ">
                @if (strtolower($status) == 'successful')
                <div class="row mb-5 ">
                    <div class="col-md-12">
                        <div class="card bg-transparent" style="border-color: var(--primary);">
                            <div class="card-body">
                                Dear {{ strtoupper(decrypt(session('user'))['name']) }},
                                <br>
                                <br>
                                Your payment was processed successfully, but we experienced a delay in creating your order.
                                <br>
                                Our support team has been notified and will follow up with you shortly. For immediate
                                assistance, please contact us at +968 {{env('SUPPORT_CONTACT')}} or email us at <a
                                    href="mailto:{{env('SUPPORT_EMAIL')}}">{{env('SUPPORT_EMAIL')}}</a>
                                .
                            </div>
                        </div>
                    </div>

                </div>
                @else
                <div class="row mb-5 ">
                    <div class="col-md-12">
                        <div class="card bg-transparent" style="border-color: var(--primary);">
                            <div class="card-body">
                                Dear {{ strtoupper(user()['name']) }},
                                <br>
                                <br>
                                Your payment was {{ $status }}. Please try again, or contact our support team at
                                +968 {{env('SUPPORT_CONTACT')}} or <a
                                    href="mailto:{{env('SUPPORT_EMAIL')}}">{{env('SUPPORT_EMAIL')}}</a>
                                for further assistance.
                                .
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <a href="{{ route('checkout') }}" class="btn btn-dark mt-2"> Try Again </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection