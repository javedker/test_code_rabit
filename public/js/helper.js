let cartCountDiv = $('.cart-count');
let headerCartDiv = $('#header-cart-div');
let sidebarCartTotalPrice = 0;
let totalCartItem = 0;

var fogLoader;

$(document).ready(function () {
    cartCount()
})

function route(name) {
    let appURL = $('#app-url').val()
    return `${appURL}/${name}`;
}

const headers = () => {
    return {
        "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
    };
};


const toast = {
    show(message = "Success!", type = "success") {
        const toast = document.getElementById('liveToast');
        const toastMsg = document.getElementById('toastMessage');

        // Set message
        toastMsg.textContent = message;

        // Reset and apply classes
        toast.className = 'toast align-items-center text-white border-0';
        toast.classList.add(`bg-${type}`);

        // Show Bootstrap Toast
        const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
        bsToast.show();
    },

    success(message) {
        this.show(message, 'success');
    },
    error(message) {
        if (message == "Kindly Sign In to continue!") {
            setTimeout(() => {
                location.href = route('sign-in')
            }, 2000)
        }
        this.show(message, 'danger');
    },
    info(message) {
        this.show(message, 'info');
    },
    warning(message) {
        this.show(message, 'warning');
    }
};



function cartCount() {

    $.ajax({
        url: route('cart/count'),
        headers: headers(),
        method: 'post',
        success: function (response) {
            cartCountDiv.show()
            cartCountDiv.each(function () {
                $(this).html(response)
                totalCartItem = response
            });
        },
        error(xhr) {
            $(this).html(0)
        }
    });
}


$(".tp-cart-toggle").on("click", function () {
    $(".tp-cart-info-area").addClass("tp-sidebar-opened");
    $(".cartbody-overlay").addClass("opened");
    renderHeaderCart()
});
function renderHeaderCart() {
    showCartPlaceholders()

    let headerCartMainDiv = $('#header-cart-main-div');
    let headerCartMainEmptyDiv = $('#header-cart-main-empty-div')
    getCart(res => {
        headerCartDiv.empty()
        if (!res.error) {
            headerCartMainDiv.show()
            headerCartMainEmptyDiv.hide()
            renderSidebarCart(res.data)
        } else {
            headerCartMainDiv.hide()
            headerCartMainEmptyDiv.show()
            $('#sidebar-total').html('')
        }
    })
}
function showCartPlaceholders() {
    headerCartDiv.empty();

    // Single centered Bootstrap spinner
    let spinner = `
        <div class="d-flex justify-content-center align-items-center py-5">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

    headerCartDiv.html(spinner);
}

function getCart(callback) {
    $.ajax({
        url: route('cart/get'),
        headers: headers(),
        method: 'post',
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}


function renderSidebarCart(items) {

    sidebarCartTotalPrice = 0
    items.forEach((item) => {

        sidebarCartTotalPrice = sidebarCartTotalPrice + parseFloat(item.price)
        const itemDiv = `
<li>
<div class="tpcart__img position-relative">
    <img src="${route('media/' + setMedia(item.thumbnail))}" alt="">
 
</div>
    <div class="tpcart__item">
        <div class="tpcart__content text-center mt-2">
            <span class="tpcart__content-title d-block">
                <a href="#">${item.name}</a>
            </span>
            <div class="tpcart__cart-price">
                <span class="quantity">${item.qty} x</span>
                <span class="new-price">${formatPrice(item.price)}</span>
            </div>
        </div>
    </div>
</li>`;


        headerCartDiv.append(itemDiv)

    })
    $('#sidebar-total').html(formatPrice(sidebarCartTotalPrice))
}
function formatPrice(amount) {
    if (isNaN(amount)) return 'OMR 0.000';

    return new Intl.NumberFormat('en-OM', {
        style: 'currency',
        currency: 'OMR',
        minimumFractionDigits: 3,
        maximumFractionDigits: 3
    }).format(amount);
}


function removeFromCart(slug, callback) {
    $.ajax({
        url: route('cart/remove'),
        headers: headers(),
        method: 'post',
        data: {
            slug
        },
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}
function clearCart(callback) {
    $.ajax({
        url: route('cart/clear'),
        headers: headers(),
        method: 'post',
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}

function removeItemFromFavorites(slug, callback) {
    $.ajax({
        url: route('favorites/remove'),
        headers: headers(),
        method: 'post',
        data: {
            slug
        },
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}

function addToFavorites(slug, callback) {
    $.ajax({
        url: route('favorites/set'),
        headers: headers(),
        method: 'post',
        data: {
            slug
        },
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}
function removeItemFromCompare(slug, callback) {
    $.ajax({
        url: route('compares/remove'),
        headers: headers(),
        method: 'post',
        data: {
            slug
        },
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}

function addToCompare(slug, callback) {
    $.ajax({
        url: route('compares/set'),
        headers: headers(),
        method: 'post',
        data: {
            slug
        },
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}

function formatOrderDate(isoString) {
    const d = new Date(isoString);
    return d.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}


function validateMobileNumber(input) {
    // Remove any non-digit characters
    let cleaned = input.value.replace(/\D/g, '');

    // Limit to 8 digits
    cleaned = cleaned.slice(0, 8);

    input.value = cleaned;

    // Optional: Oman mobile validation (first digit rule)
    const isValidOmanNumber = /^[279]\d{7}$/.test(cleaned);

    if (!isValidOmanNumber && cleaned.length === 8) {
        input.setCustomValidity("Invalid Oman mobile number. It should start with 2, 7, or 9.");
    } else {
        input.setCustomValidity(""); // Clear error
    }
}

if ($('#togglePassword').length && $('#password').length && $('#password_confirmation').length) {
    $('#togglePassword').on('change', function () {
        const type = $(this).is(':checked') ? 'text' : 'password';
        $('#password, #password_confirmation').attr('type', type);
    });
}


function validatePasswordPolicy(password) {
    const policyRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    if (password.includes(" ")) {
        return "Password must not contain spaces.";
    } else if (!policyRegex.test(password)) {
        return "Password must be at least 8 characters, include uppercase, lowercase, number, and special character.";
    }
    return "";
}

$('#password, #password_confirmation').on('input keydown keyup', function (e) {
    const password = $('#password').val().trim();
    const confirmPassword = $('#password_confirmation').val().trim();

    // reset both messages every cycle
    $('#password-error').text('');
    $('#password-confirm-error').text('');

    // 1) policy
    let passwordError = validatePasswordPolicy(password);

    // 2) confirm match
    if (confirmPassword && password !== confirmPassword) {
        $('#password-confirm-error').text('Passwords do not match.');
    }

    // 3) Caps Lock (jQuery-safe)
    let capsOn = false;
    const isKeyEvent = e.type === 'keydown' || e.type === 'keyup';

    if (isKeyEvent && e.originalEvent && typeof e.originalEvent.getModifierState === 'function') {
        capsOn = e.originalEvent.getModifierState('CapsLock');
    } else if (isKeyEvent && e.originalEvent) {
        // fallback heuristic if getModifierState isn't available
        const code = e.which || e.keyCode;
        const ch = String.fromCharCode(code || 0);
        if (/[A-Z]/i.test(ch)) {
            const isUpper = ch === ch.toUpperCase() && ch !== ch.toLowerCase();
            const isLower = ch === ch.toLowerCase() && ch !== ch.toUpperCase();
            // Caps ON if letter is upper w/o shift OR lower with shift
            if ((isUpper && !e.shiftKey) || (isLower && e.shiftKey)) {
                capsOn = true;
            }
        }
    }

    if (capsOn) {
        if (!passwordError.includes('⚠️ Caps Lock is ON')) {
            passwordError += (passwordError ? ' ' : '') + `⚠️ Caps Lock is ON`;
        }
    }

    if (passwordError) {
        $('#password-error').text(passwordError);
    }
});
if ($("#capslock-warning").length === 0) {
    $("#sign-in-password").after('<small id="capslock-warning" style="display:block;"></small>');
}

// Caps Lock detection on the password field
$('#sign-in-password').on('keydown keyup', function (e) {
    let capsOn = false;

    if (e.originalEvent && typeof e.originalEvent.getModifierState === 'function') {
        capsOn = e.originalEvent.getModifierState('CapsLock');
    }

    if (capsOn) {
        $('#capslock-warning').text('⚠️ Caps Lock is ON');
    } else {
        $('#capslock-warning').text('');
    }
});
$('#sign-up-form').on('submit', function (e) {
    const password = $('#password').val().trim();
    const confirmPassword = $('#password_confirmation').val().trim();

    let valid = true;

    // Validate password
    const passwordError = validatePasswordPolicy(password);
    if (passwordError) {
        $('#password-error').text(passwordError);
        valid = false;
    }

    // Validate confirm match
    if (password !== confirmPassword) {
        $('#password-confirm-error').text('Passwords do not match.');
        valid = false;
    }

    if (!valid) {
        e.preventDefault(); // Stop form submission
    }
});

const SEARCH_HISTORY_KEY = 'recentSearches';

function storeSearchTerm(term) {
    if (!term || term.length < 2) return;

    let stored = JSON.parse(localStorage.getItem(SEARCH_HISTORY_KEY) || '[]');
    stored = stored.filter(item => item.toLowerCase() !== term.toLowerCase()); // remove duplicate
    stored.push(term);
    localStorage.setItem(SEARCH_HISTORY_KEY, JSON.stringify(stored.slice(-10))); // max 10 entries
}

function renderSuggestions(items, isStored = false) {
    const $suggestionsBox = $('#search-suggestions').empty().show();
    const $ul = $('<ul></ul>');

    if (items.length === 0) {
        $ul.append('<li class="no-result"><i class="fal fa-times-circle"></i> No results found</li>');
    } else {
        $.each(items, function (i, item) {
            const $li = $(`<li class="${isStored ? 'stored-search' : ''}"><i class="fal fa-search"></i> ${item}</li>`);
            $li.on('click', function () {
                $('#search-input').val(item);
                $('#search-suggestions').hide();
                storeSearchTerm(item);
                window.location.href = route(`shop?search=${encodeURIComponent(item)}`);
            });
            $ul.append($li);
        });
    }

    $suggestionsBox.append($ul);
}


// 🔍 On keyup — Call backend for live search
$('#search-input').on('keyup', function (e) {
    const query = $(this).val().trim();
    if (query.length < 2) {
        $('#search-suggestions').hide();
        return;
    }
    if (e.which === 13 || e.keyCode === 13) { // 13 = Enter key
        location.href = route(`shop?search=${query}&page=1`)
        return
    }

    $.ajax({
        url: route('shop/search'),
        headers: headers(),
        method: 'GET',
        data: { query },
        success: function (response) {
            const names = response.data.map(item => item.name);
            renderSuggestions(names);
        },
        error(xhr) {
            renderSuggestions([]);
        }
    });
});

// 📂 On focus — Show recent searches from localStorage
$('#search-input').on('focus', function () {
    const stored = JSON.parse(localStorage.getItem(SEARCH_HISTORY_KEY) || '[]');
    renderSuggestions(stored.reverse());
});

// 🧽 Hide suggestions when clicking outside
$(document).on('click', function (e) {
    if (!$(e.target).closest('.search-info').length) {
        $('#search-suggestions').hide();
    }
});



// --- Small search: helpers ---
function storeSearchTermSmall(term) {
    if (!term || term.length < 2) return;

    let storedSmall = JSON.parse(localStorage.getItem(SEARCH_HISTORY_KEY) || '[]');
    storedSmall = storedSmall.filter(t => t.toLowerCase() !== term.toLowerCase()); // de-dup
    storedSmall.push(term);
    localStorage.setItem(SEARCH_HISTORY_KEY, JSON.stringify(storedSmall.slice(-10)));
}

function renderSuggestionsSmall($input, items, isStoredSmall = false) {
    // find matching suggestion box near the input
    const $boxSmall = $('.search-suggestions-small').empty().show();
    const $ulSmall = $('<ul></ul>');

    if (items.length === 0) {
        $ulSmall.append('<li class="no-result"><i class="fal fa-times-circle"></i> No results found</li>');
    } else {
        $.each(items, function (i, item) {

            const $liSmall = $(`<li class="${isStoredSmall ? 'stored-search' : ''}">
                            <i class="fal fa-search"></i> ${item}
                          </li>`);
            $liSmall.on('click', function () {
                $input.val(item);
                $boxSmall.hide();
                storeSearchTermSmall(item);
                window.location.href = route(`shop?search=${encodeURIComponent(item)}`);
            });
            $ulSmall.append($liSmall);
        });
    }

    $boxSmall.append($ulSmall);
}

// 🔎 Keyup — for all .search-input-small
$(document).on('keyup', '.search-input-small', function (e) {

    const $input = $(this);
    const qSmall = $input.val().trim();

    if (qSmall.length < 2) {
        $input.siblings('.search-suggestions-small').hide();
        return;
    }
    if (e.which === 13 || e.keyCode === 13) {
        e.preventDefault()
        location.href = route(`shop?search=${qSmall}&page=1`)
        return
    }
    $.ajax({
        url: route('shop/search'),
        headers: headers(),
        method: 'GET',
        data: { query: qSmall },
        success: function (response) {
            const namesSmall = (response.data || []).map(item => item.name);
            renderSuggestionsSmall($input, namesSmall);
        },
        error() {
            renderSuggestionsSmall($input, []);
        }
    });
});

// 📂 Focus — show recent (for all .search-input-small)
$(document).on('focus', '.search-input-small', function () {
    const $input = $(this);
    const storedSmall = JSON.parse(localStorage.getItem(SEARCH_HISTORY_KEY) || '[]');
    renderSuggestionsSmall($input, storedSmall.slice().reverse(), true);
});

// 🧽 Hide when clicking outside (for each input wrapper)
$(document).on('click', function (e) {
    if (!$(e.target).closest('.search-info-small').length) {
        $('.search-suggestions-small').hide();
    }
});


function getAddresses(callback) {
    $.ajax({
        url: route('addresses/get'),
        headers: headers(),
        method: 'post',
        success: function (response) {
            callback(response)
        },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message })
        }
    });
}

/**
 * validateField(el, type, errorElementId, maxLength, isSpace)
 * type: 'string' | 'numeric' | 'email' | 'mixed' | 'all'
 * - string: letters only (A–Z, a–z) + optional spaces
 * - numeric: digits only
 * - email: standard email pattern (value not auto-scrubbed except spaces if disallowed)
 * - mixed: letters + digits (+ optional spaces)
 * - all: no character filter (still enforces maxLength & space rule)
 * isSpace: boolean → allow spaces?
 */
function validateField(el, type = 'all', errorElementId = null, maxLength = null, isSpace = true) {
    const valBefore = el.value;
    const space = isSpace ? ' ' : '';
    const filters = {
        string: new RegExp(`[^a-zA-Z${isSpace ? ' ' : ''}]`, 'g'),
        numeric: /[^0-9]/g,
        mixed: new RegExp(`[^a-zA-Z0-9${isSpace ? ' ' : ''}]`, 'g'),
        all: isSpace ? null : /\s/g // if all + no spaces, strip whitespace
    };

    let v = valBefore;

    // 1) Sanitize characters (except email where we only control spaces)
    if (type === 'email') {
        if (!isSpace) v = v.replace(/\s/g, '');
    } else {
        const f = filters[type] || filters.all;
        if (f) v = v.replace(f, '');
    }

    // 2) Enforce maxLength (if provided and positive)
    if (Number.isInteger(maxLength) && maxLength > 0) {
        v = v.slice(0, maxLength);
    }

    // 3) Apply back to input
    if (v !== valBefore) el.value = v;

    // 4) Validate and show error
    let err = '';

    switch (type) {
        case 'string':
            if (v && /[^a-zA-Z\s]/.test(v)) err = 'Only letters are allowed.';
            break;
        case 'numeric':
            if (v && /[^0-9]/.test(v)) err = 'Only numbers are allowed.';
            break;
        case 'mixed':
            if (v && /[^a-zA-Z0-9\s]/.test(v)) err = 'Only letters and numbers are allowed.';
            break;
        case 'email':
            // simple, safe email regex
            const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v);
            if (v && !emailOk) err = 'Please enter a valid email address.';
            break;
        case 'all':
            // no format restriction
            break;
    }

    // Space rule error (only if user typed spaces and rule forbids)
    if (!isSpace && /\s/.test(v)) {
        err = (type === 'email') ? 'Spaces are not allowed in emails.' : 'Spaces are not allowed.';
    }

    // Max length message
    if (Number.isInteger(maxLength) && maxLength > 0 && v.length === maxLength && valBefore.length > v.length) {
        // user tried to exceed; optional message if you want:
        // err = `Maximum ${maxLength} characters allowed.`;
    }

    // 5) Output error
    if (errorElementId) {
        const elErr = document.getElementById(errorElementId);
        if (elErr) {
            elErr.textContent = err;
            elErr.classList.toggle('text-danger', !!err);
        }
    }
    el.setCustomValidity(err || '');
}



function showMainSpinner() {
    $('#main-content').addClass('d-none')
    $('#main-spinner').removeClass('d-none')

}
function hideMainSpinner() {
    $('#main-content').removeClass('d-none')
    $('#main-spinner').addClass('d-none')
}


$('#header-clear-cart').on('click', function (e) {
    e.preventDefault();

    const button = $(this);
    const spinner = $(`#header-clear-cart-spinner`);

    button.hide();
    spinner.show();
    clearCart(res => {
        if (!res.error) {
            spinner.hide();
            button.show();
            cartCount();
            renderHeaderCart()
            $(".tp-cart-info-area").removeClass("tp-sidebar-opened");
            $(".cartbody-overlay").removeClass("opened");

            toast.success(res.message);
        } else {
            button.show();
            spinner.hide()
            toast.error(res.message);
        }
    });
});/**
 * Adds .main-menu-active to:
 * - "New Arrivals" when URL hash is #new-arrivals
 * - "Offer of the month" when URL hash is #special-offers
 * - "Shop By Brands" on any /articles page
 * - Specific brand in submenu when ?brand=... (or /articles/{brand})
 */
function renderActiveRoute(rootSelector = '.header-render-active-menu') {
    const nav = document.querySelector(rootSelector);
    if (!nav) return;

    const setActive = (el) => el && el.classList.add('main-menu-active');
    const clearAll = () => nav.querySelectorAll('a.main-menu-active')
        .forEach(a => a.classList.remove('main-menu-active'));

    clearAll();

    const { pathname, hash, search } = window.location;
    const params = new URLSearchParams(search);
    const hashLower = (hash || '').toLowerCase();

    // Anchors on home
    if (hashLower === '#new-arrivals') {
        setActive(nav.querySelector('a[href$="#new-arrivals"]'));
    }
    if (hashLower === '#special-offers') {
        setActive(nav.querySelector('a[href$="#special-offers"]'));
    }

    // Articles route (Shop By Brands)
    const parts = pathname.toLowerCase().replace(/\/+$/, '').split('/').filter(Boolean);
    const idxArticles = parts.indexOf('shop');
    const isArticles = idxArticles !== -1;

    if (isArticles) {
        const shopA = nav.querySelector('li.has-dropdown > a[href*="shop"]');
        setActive(shopA);
    }

    // Brand detection: ?brand=... or /articles/{brand}
    let brand = params.get('brand');
    if (!brand && isArticles && parts[idxArticles + 1]) {
        brand = decodeURIComponent(parts[idxArticles + 1]);
    }
    if (brand) {
        const brandUpper = brand.toUpperCase();
        nav.querySelectorAll('.submenu a').forEach(a => {
            const u = new URL(a.href, window.location.origin);
            const qBrand = (new URLSearchParams(u.search).get('brand') || '').toUpperCase();
            const pathBrand = (u.pathname.split('/').pop() || '').toUpperCase();
            if (qBrand === brandUpper || pathBrand === brandUpper) {
                setActive(a);
            }
        });
    }
}

/* Sticky header variant: just call the core with sticky menu's root */
function renderStickyHeaderRoute() {
    renderActiveRoute('.header-render-active-menu-sticky');
    // or '.header-render-active-menu' if that’s your actual class
}

/* Run on load + URL changes */
function updateMenus() {
    renderActiveRoute();
    renderStickyHeaderRoute();
}

document.addEventListener('DOMContentLoaded', updateMenus);
window.addEventListener('hashchange', updateMenus);
window.addEventListener('popstate', updateMenus);

function setMedia(img) {
    if (!img) return '';
    // replace forward/back slashes with underscores (route-safe)
    return String(img).replace(/[\/\\]/g, '$');
}

