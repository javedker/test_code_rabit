<div class="tpsideinfo">
    <button class="tpsideinfo__close">Close<i class="fal fa-times ml-10"></i></button>

    <div class="tpsideinfo__nabtab pt-30">
        {{-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Menu</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                    type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Categories</button>
            </li>
        </ul> --}}
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                tabindex="0">
                <div class="mobile-menu"></div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                tabindex="0">
                <div class="tpsidebar-categories mobile-categories" id="mobile-menu">
                    <ul>
                        @foreach (getCategories() as $segment => $categories)
                            <li class="menu-item-has-children">
                                <a>
                                    {{ $segment }}
                                </a>
                                <ul class="submenu mb-10">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('articles', ['category' => $category]) }}">
                                                {{ $category }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tpsideinfo__account-link">
        <a href="{{ route('auth.sign-in') }}"><i class="fal fa-user"></i> Login / Register</a>
    </div>
    <div class="tpsideinfo__wishlist-link">
        <a href="{{ route('favorites') }}" target="_parent"><i class="fal fa-heart"></i> Favorites</a>
    </div>
</div>
<div class="body-overlay"></div>

