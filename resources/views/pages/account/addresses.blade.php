@extends('layout.master')

@section('title', 'Your Addresses')



@section('content')
    <section class="py-5">
        <div class="container account" id="account-addresses">
            <div class="row mb-2">
                <div class="col-4 offset-md-2">
                    <h2 class="h4 mb-0">Addresses</h2>
                </div>
                <div class="col-4 text-end">
                    <a data-bs-toggle="modal" href="" class="btn tptrack__submition_custom"
                        data-bs-target="#addAddressModal">
                        <i class="fal fa-plus mx-2"></i> Add new
                    </a>
                </div>
            </div>


            <!-- Address list -->
            <div class="row justify-content-center">
                <div class="col-lg-8 d-flex flex-column addr-wrap" id="addresses-list">
                    <!-- Placeholder while loading -->
                    @for($i = 0; $i < 3; $i++)
                        <div class="card mb-3 p-3">
                            <div class="placeholder-glow">
                                <span class="placeholder col-6 mb-2"></span>
                                <span class="placeholder col-8 mb-2"></span>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
    <x-address-modal />

@endsection


@push('scripts')
    <script src="{{asset('js/addresses.js')}}"></script>
@endpush