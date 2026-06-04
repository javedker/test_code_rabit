<style>
    .tp-breadcrumb__link {
        display: flex;
        align-items: center;
        font-size: 16px;
        /* match your breadcrumb text size */
    }

    .tp-breadcrumb__link a,
    .tp-breadcrumb__link span {
        font-size: inherit;
    }

    .tp-breadcrumb__link .breadcrumb-sep {
        font-size: 0.9em;
        /* keep arrow proportional to text */
        margin: 0 8px;
        /* spacing left & right */
        vertical-align: middle;
        /* align with text baseline */
        color: #666;
        /* subtle grey like most breadcrumbs */
    }
</style>
<section class="breadcrumb__area mt-4 tp-breadcrumb__bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-7 col-lg-12 col-md-12 col-12">
                <div class="tp-breadcrumb">
                    <div class="tp-breadcrumb__link">
                        <a href="{{ route('home') }}">Home</a>
                        @if ($current)
                            <i class="fas fa-angle-right breadcrumb-sep"></i>
                            <span class="text-capitalize">{{ $current }}</span>
                        @endif
                        <i class="fas fa-angle-right breadcrumb-sep"></i>
                        <a href="{{$titleUrl}}" class="text-capitalize">{{ $title }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>