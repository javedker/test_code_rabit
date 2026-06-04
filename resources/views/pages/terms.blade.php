@extends('layout.master')

@section('title', 'Terms & Conditions')

@section('content')

    <section class="track-area pt-80 pb-40">
        <div class="container">
            {!! $terms['data'] !!}
        </div>
    </section>

    {{-- Password Policy Modal --}}

@endsection