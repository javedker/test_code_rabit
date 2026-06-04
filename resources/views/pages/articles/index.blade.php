@extends('layout.master')

@section('title', 'Shop')

@section('content')

    {{-- BreadCrumb --}}
    <x-breadcrumb title="Shop" />

    <div class="product-area pt-20 pb-20">
        <div class="container">
            <div class="row">

                {{-- Sidebar for Desktop --}}
                <div class="col-lg-2 col-md-12 d-none d-lg-block">
                    <div class="tpsidebar product-sidebar__product-category">
                        @include('components.filters')
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="col-lg-10 col-md-12">

                    {{-- Mobile Filter Button --}}


                    {{-- Offcanvas for Mobile --}}
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="filtersOffcanvas">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title">Filters</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body">
                            @include('components.filters')
                        </div>
                    </div>

                    {{-- Product Listing --}}
                    <div class="product-sidebar__product-item">
                        <div class="product-filter-content mb-40">
                            <div class="row align-items-center">

                                <div class="col-sm-3">
                                    <div class="row align-items-center mb-3">
                                        {{-- Filter button (mobile only) --}}
                                        <div class="col-6 d-lg-none text-start">
                                            <button class="btn btn-outline-dark" data-bs-toggle="offcanvas"
                                                data-bs-target="#filtersOffcanvas">
                                                <i class="fas fa-filter me-2"></i> Filters
                                            </button>
                                        </div>

                                        {{-- Remove filters link --}}
                                        <div class="col-6 col-lg-12 text-end">
                                            <a href="{{ route('articles') }}" id="remove-filters"
                                                class="text-danger d-inline-flex align-items-center d-none">
                                                <i class="fas fa-times me-2"></i> Remove all filters
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-3 text-start mb-3">
                                    <div class="product-item-count">
                                        <span><b id="total-products">Loading..</b> Products</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="product-navtabs d-flex justify-content-end align-items-center">
                                        <div class="tp-shop-selector">
                                            <select id="show-articles" class="tptrack__custom_select mx-2">
                                                <option value="12">Show 12</option>
                                                <option value="24">Show 24</option>
                                                <option value="48">Show 48</option>
                                            </select>
                                        </div>
                                        <div class="tp-shop-selector mx-2">
                                            <select id="sort-by" class="tptrack__custom_select mx-2">
                                                <option value="">Sort By</option>
                                                <option value="asc">Lowest Price</option>
                                                <option value="desc">Highest Price</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-50">
                            <div class="col-lg-12">
                                <div class="id nav-popular" role="tabpanel" aria-labelledby="nav-popular-tab">
                                    <div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 tpproduct"
                                        id="articles-div">
                                    </div>
                                </div>

                                <div id="no-products" class="" style="display: none;">
                                    <div class="w-100 d-flex justify-content-center align-items-center py-5">
                                        <div class="text-center p-4 rounded-4"
                                            style="max-width:620px;background:#f9fafb;border:1px solid #eef0f3;">
                                            <h5 class="mt-3 mb-1">No products found</h5>
                                            <p class="text-muted mb-3" style="line-height:1.5;">
                                                We couldn’t find any items matching your filters. Try adjusting or clearing
                                                them.
                                            </p>
                                            <a href="{{ route('articles') }}" class="btn btn-outline-dark">
                                                <i class="fas fa-undo me-2"></i>Clear all filters
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xxl-12">
                                <div class="basic-pagination text-center pb-50" id="pagination">
                                    <!-- Dynamic pagination links will appear here -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/jquery-ui.js')}}"></script>
    <script src="{{asset('js/articles.js')}}"></script>
@endpush