@extends('layout.master')

@section('title', 'Server Error')

@section('content')
    <section class="erroe-area pt-70 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="eperror__wrapper text-center">
                        <div class="tperror__thumb mb-35">
                            <img src="assets/img/icon/error.png" alt="">
                        </div>
                        <div class="tperror__content">
                            <h4 class="tperror__title mb-25">500</h4>
                            @if (isset($message) && !empty($message))
                                <p>
                                    {{ $message }}
                                </p>
                            @else
                                <p>
                                    Sorry, we couldn’t find the page you where looking for. We suggest that <br> you return
                                    to
                                    homepage.
                                </p>
                            @endif
                            <a class="tpsecondary-btn tp-color-btn tp-error-btn" href="{{ route('home') }}"><i
                                    class="fal fa-long-arrow-left"></i>
                                Back To Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
