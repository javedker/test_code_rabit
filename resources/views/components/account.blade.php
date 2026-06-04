<div class="offcanvas offcanvas-end" tabindex="-1" id="userOffcanvas" aria-labelledby="userOffcanvasLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title text-uppercase small" id="userOffcanvasLabel">
            Hello, {{signedUser('name')}}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body text-primary">

        <ul class="list-unstyled mb-4 account-menu-list">
            <li>
                <a href="{{route('account')}}" class="account-menu-link">
                    <i class="fas fa-user me-3 icon-bg"></i> <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('account.orders') }}" class="account-menu-link">
                    <i class="fas fa-box-open me-3 icon-bg"></i> <span>Order History</span>
                </a>
            </li>

            <li>
                <a href="{{route('account.security')}}" class="account-menu-link">
                    <i class="fas fa-lock me-3 icon-bg"></i> <span>Security</span>
                </a>
            </li>
            <li>
                <a href="{{route('account.addresses')}}" class="account-menu-link">
                    <i class="fas fa-map-marker-alt me-3 icon-bg"></i> <span>Addresses</span>
                </a>
            </li>
            <li>
                <a href="{{ route('home.faqs') }}" class="account-menu-link">
                    <i class="fas fa-question-circle me-3 icon-bg"></i> <span>FAQs</span>
                </a>
            </li>
            <li>
                <a href="{{ route('home.terms') }}" class="account-menu-link">
                    <i class="fas fa-file-contract me-3 icon-bg"></i> <span>Terms &amp; Conditions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('auth.sign-out') }}" class="account-menu-link">
                    <i class="fas fa-sign-out-alt me-3 icon-bg"></i> <span>Sign Out</span>
                </a>
            </li>
        </ul>


    </div>
</div>