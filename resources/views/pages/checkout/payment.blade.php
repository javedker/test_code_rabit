@extends('layout.master')

@section('title', 'Checkout')

@section('content')
<div class="container py-5 checkout-spinner">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <!-- Bootstrap Spinner -->
                    <div class="spinner-border text-primary" style="width: 10rem; height: 10rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-3 text-primary fw-bold">Redirecting to Payment Page…</div>
                    <form action="{{ env('PAYMENT_URL') }}" method="post" id="payment-form-submit">
                        @csrf
                        <input type="hidden" name="access_code" value="{{ env('ACCESS_CODE') }}">
                        <input type="hidden" name="encRequest" value="{{ $token }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Show hourglass when the page starts loading/reloading
        $('#payment-form-submit').submit();
    });
</script>
@endpush