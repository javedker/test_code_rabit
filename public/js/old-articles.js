const articlesDiv = $('#articles-div');
const paginationDiv = $('#pagination');
const filterCategoryDiv = $('#filter-category');
const filterTypesDiv = $('#filter-types');       // ensure your HTML has id="filter-type"
const filterBrandsDiv = $('#filter-brands');
const filterSegmentsDiv = $('#filter-segments');
const totalProductsDiv = $('#total-products');
const showArticle = $('#show-articles');     // <select id="show-articles">

// State
let limit;
let currentPage = 1;
let firstLoad = true; // may flip to false if URL params exist
let inStock = 0;
let specialPrice = 0;
let sort = "";
let search = "";
let isQueryParams = checkQueryParams();

let selectedFilters = {
    brands: [],
    categories: [],
    segments: [],
    types: [],
};

$(document).ready(function () {
    // page size from select (fallback to 12)
    limit = parseInt(showArticle.val(), 10) || 12;

    // querystring -> state (preload)
    hydrateStateFromQuery();

    // If URL has any filter params, render them immediately and skip API-based filter rendering
    if (hasSelectedFromQuery()) {
        renderFiltersFromParams();   // draw UI from URL
        firstLoad = false;           // don't overwrite with API filters
    }

    // reload when user changes page-size
    showArticle.on('change', function () {
        limit = parseInt($(this).val(), 10) || limit;
        currentPage = 1;
        getArticles(currentPage);
    });

    // initial load
    getArticles(currentPage);
});

/* ----------------------- Helpers ----------------------- */
function uniqPush(arr, val) {
    val = String(val);
    if (!arr.includes(val)) arr.push(val);
}
function removeVal(arr, val) {
    val = String(val);
    return arr.filter(x => x !== val);
}
function safeText(text) {
    return String(text ?? '').replace(/[&<>"']/g, s => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s]));
}
function buildProductUrl(slug) {
    try {
        if (typeof route === 'function') return route(`shop/details/${slug}`);
    } catch (e) { }
    return `/shop/details/${encodeURIComponent(slug)}`;
}
function hasSelectedFromQuery() {
    return (
        selectedFilters.brands.length ||
        selectedFilters.categories.length ||
        selectedFilters.segments.length ||
        selectedFilters.types.length
    );
}
// Robust extractor for arrays that may be strings or objects with different keys
function valLabelFromItem(item, preferKeys = ['id', 'value', 'type', 'category', 'segment', 'name', 'label']) {
    if (typeof item === 'string' || typeof item === 'number') {
        const v = String(item);
        return { value: v, label: v };
    }
    if (item && typeof item === 'object') {
        // try to find a "value" and "label"
        let value, label;
        for (const k of preferKeys) {
            if (item[k] != null) { value = String(item[k]); break; }
        }
        // label fallback: name/label/value/type...
        const labelKeys = ['name', 'label', 'type', 'category', 'segment', 'value', 'id'];
        for (const k of labelKeys) {
            if (item[k] != null) { label = String(item[k]); break; }
        }
        value = value ?? label ?? '';
        label = label ?? value;
        return { value, label };
    }
    return { value: '', label: '' };
}

/* ------------------ Placeholders ------------------ */
function showArticlePlaceholders() {
    articlesDiv.empty();
    let placeholderHTML = '';
    for (let i = 0; i < limit; i++) {
        placeholderHTML += `
      <div class="col">
        <div class="tpproduct tpproductitem mb-15 p-relative">
          <div class="tpproduct__thumb">
            <div class="tpproduct__thumbitem p-relative placeholder-glow">
              <div class="placeholder shine" style="width:100%; height:200px;"></div>
            </div>
          </div>
          <div class="tpproduct__content-area placeholder-glow">
            <span class="placeholder shine col-4 d-block mb-2" style="height:14px;"></span>
            <h3 class="tpproduct__title mb-5">
              <span class="placeholder shine col-6 d-block" style="height:16px;"></span>
            </h3>
            <div class="tpproduct__priceinfo p-relative">
              <div class="tpproduct__ammount">
                <span class="placeholder shine col-3 d-block" style="height:14px;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>`;
    }
    articlesDiv.html(placeholderHTML);
}

function renderFilterPlaceholders($container, rows = 5) {
    $container.empty();
    for (let i = 0; i < rows; i++) {
        $container.append(`
      <div class="form-check disabled mb-2 placeholder-glow">
        <span class="placeholder col-1 me-2" style="height:18px;"></span>
        <span class="placeholder col-8" style="height:18px;"></span>
      </div>
    `);
    }
}

function showAllFilterPlaceholders() {
    renderFilterPlaceholders(filterBrandsDiv, 6);
    renderFilterPlaceholders(filterCategoryDiv, 6);
    renderFilterPlaceholders(filterSegmentsDiv, 6);
    renderFilterPlaceholders(filterTypesDiv, 6);
}

/* ------------------ Fetch & Render ------------------ */
function getArticles(page) {
    showArticlePlaceholders();

    const offset = (page - 1) * limit;

    $.ajax({
        url: typeof route === 'function' ? route('shop/get') : '/shop/get',
        method: 'get',
        data: {
            limit,
            offset,
            brands: selectedFilters.brands,
            categories: selectedFilters.categories,
            segments: selectedFilters.segments,
            specialPrice,
            inStock,
            sort,
            search,
            types: selectedFilters.types,
        },
        success: function (response) {
            if (!response.error) {
                totalProductsDiv.text(response.total ?? 0);

                // Only render API filters if we didn't already render from URL
                if (firstLoad || isQueryParams) {
                    renderFilters(response.other?.filters || {});
                    firstLoad = false;
                }
                renderArticles(response.data || []);
                renderPagination(response.total || 0, page);
                // renderFilters(response.other.filters)
                updateQueryString(); // keep URL in sync after load
            } else {
                articlesDiv.empty();
            }
        },
        error: function (xhr) {
            try {
                const j = JSON.parse(xhr.responseText);
                console.error(j.message || 'Request failed');
            } catch (e) {
                console.error('Request failed');
            }
            articlesDiv.empty();
        }
    });
}

function renderArticles(articles) {
    articlesDiv.empty();
    articles.forEach(article => {
        const slug = buildProductUrl(article.slug);
        const brand = safeText(article.brand_name ?? '');
        const name = safeText(article.name ?? '');
        const thumb = safeText(article.thumbnail ?? '');

        const html = `
      <div class="col">
        <div class="tpproduct tpproductitem mb-15 p-relative">
          <div class="tpproduct__thumb">
            <div class="tpproduct__thumbitem p-relative">
              <a href='' class="position-absolute top-0 end-0 mx-2 mt-2"><i class='far fa-heart text-primary'></i></a>
              <a href="${slug}">
                <img src="${thumb}" alt="product-thumb">
              </a>
            </div>
          </div>
          <div class="tpproduct__content-area text-center">
            <b >${brand}</b>
            <h3 class="tpproduct__title mb-5"><a href="${slug}">${name}</a></h3>
            <div class="tpproduct__priceinfo p-relative">
              <b class=" text-primary">
                <span>${formatPrice(article.price)}</span>
              </b>
            </div>
          </div>
        </div>
      </div>`;
        articlesDiv.append(html);
    });
}

function renderPagination(total, current) {
    paginationDiv.empty();
    const totalPages = Math.max(1, Math.ceil(total / limit));
    let html = '<ul>';

    if (current > 1) {
        html += `<li><a href="#" data-page="${current - 1}"><i class="fal fa-long-arrow-left"></i></a></li>`;
    }

    for (let i = 1; i <= 2 && i <= totalPages; i++) {
        html += paginationLink(i, current);
    }

    if (current > 4) {
        html += `<li><span class="dots">...</span></li>`;
    }

    for (let i = current - 1; i <= current + 1; i++) {
        if (i > 2 && i < totalPages - 1) {
            html += paginationLink(i, current);
        }
    }

    if (current < totalPages - 3) {
        html += `<li><span class="dots">...</span></li>`;
    }

    for (let i = Math.max(3, totalPages - 1); i <= totalPages; i++) {
        if (i > 2) {
            html += paginationLink(i, current);
        }
    }

    if (current < totalPages) {
        html += `<li><a href="#" data-page="${current + 1}"><i class="fal fa-long-arrow-right"></i></a></li>`;
    }

    html += '</ul>';
    paginationDiv.html(`<nav>${html}</nav>`);

    paginationDiv.find('a').on('click', function (e) {
        e.preventDefault();
        const page = +$(this).data('page');
        if (page && page !== currentPage) {
            currentPage = page;
            getArticles(page);
        }
    });
}

function paginationLink(page, current) {
    if (page === current) {
        return `<li><span class="current">${page > 9 ? page : `0${page}`}</span></li>`;
    }
    const label = page.toString().padStart(2, '0');
    return `<li><a href="#" data-page="${page}">${label}</a></li>`;
}

/* ------------------ Filters Rendering (API) ------------------ */
function renderFilters(filters) {
    filterBrandsDiv.empty();
    filterCategoryDiv.empty();
    filterSegmentsDiv.empty();
    filterTypesDiv.empty();

    // Brands: [{id, name}] or mixed
    if (Array.isArray(filters.brands)) {
        filters.brands.forEach(item => {
            const idRaw = item.id ?? item.sap_brand_id ?? item.value ?? item.brand_id;
            const name = item.name ?? item.label ?? String(idRaw);
            const idStr = String(idRaw);                    // 🔧 force string
            const checked = selectedFilters.brands.includes(idStr) ? 'checked' : '';

            filterBrandsDiv.append(`
      <div class="form-check">
        <input class="form-check-input filter-brands" type="checkbox"
               value="${safeText(name)}" id="brand-${safeText(idStr)}" ${checked}>
        <label class="form-check-label" for="brand-${safeText(idStr)}">${safeText(name)}</label>
      </div>
    `);
        });
    }

    // Categories: likely [{category}] or strings
    if (Array.isArray(filters.categories)) {
        filters.categories.forEach(item => {
            const { value, label } = valLabelFromItem(item, ['category', 'value', 'name', 'label']);
            const checked = selectedFilters.categories.includes(value) ? 'checked' : '';
            const v = safeText(value);
            filterCategoryDiv.append(`
        <div class="form-check">
          <input class="form-check-input filter-category" type="checkbox" value="${v}" id="category-${v}" ${checked}>
          <label class="form-check-label" for="category-${v}">${safeText(label)}</label>
        </div>
      `);
        });
    }

    // Types: supports [{type}] or [{name}] or simple strings (FIXED)
    if (Array.isArray(filters.types)) {
        filters.types.forEach(item => {
            const { value, label } = valLabelFromItem(item, ['type', 'value', 'name', 'label']);
            const checked = selectedFilters.types.includes(value) ? 'checked' : '';
            const v = safeText(value);
            filterTypesDiv.append(`
        <div class="form-check">
          <input class="form-check-input filter-types" type="checkbox" value="${v}" id="type-${v}" ${checked}>
          <label class="form-check-label" for="type-${v}">${safeText(label)}</label>
        </div>
      `);
        });
    }

    // Segments: supports [{segment}] or variants
    if (Array.isArray(filters.segments)) {
        filters.segments.forEach(item => {
            const { value, label } = valLabelFromItem(item, ['segment', 'value', 'name', 'label']);
            const checked = selectedFilters.segments.includes(value) ? 'checked' : '';
            const v = safeText(value);
            filterSegmentsDiv.append(`
        <div class="form-check">
          <input class="form-check-input filter-segments" type="checkbox" value="${v}" id="segment-${v}" ${checked}>
          <label class="form-check-label" for="segment-${v}">${safeText(label)}</label>
        </div>
      `);
        });
    }

    attachFilterHandlers();
}

/* --------- Filters Rendering from URL params (NO API) --------- */
function renderFiltersFromParams() {
    filterBrandsDiv.empty();
    filterCategoryDiv.empty();
    filterSegmentsDiv.empty();
    filterTypesDiv.empty();

    // Brands (IDs from URL)
    selectedFilters.brands.forEach(v => {
        const idStr = safeText(v);
        filterBrandsDiv.append(`
      <div class="form-check">
        <input class="form-check-input filter-brands" type="checkbox" value="${idStr}" id="brand-${idStr}" checked>
        <label class="form-check-label" for="brand-${idStr}">${idStr}</label>
      </div>
    `);
    });

    // Categories (strings from URL)
    selectedFilters.categories.forEach(v => {
        const s = safeText(v);
        filterCategoryDiv.append(`
      <div class="form-check">
        <input class="form-check-input filter-category" type="checkbox" value="${s}" id="category-${s}" checked>
        <label class="form-check-label" for="category-${s}">${s}</label>
      </div>
    `);
    });

    // Types (strings from URL)  (FIXED)
    selectedFilters.types.forEach(v => {
        const s = safeText(v);
        filterTypesDiv.append(`
      <div class="form-check">
        <input class="form-check-input filter-types" type="checkbox" value="${s}" id="type-${s}" checked>
        <label class="form-check-label" for="type-${s}">${s}</label>
      </div>
    `);
    });

    // Segments (strings from URL)
    selectedFilters.segments.forEach(v => {
        const s = safeText(v);
        filterSegmentsDiv.append(`
      <div class="form-check">
        <input class="form-check-input filter-segments" type="checkbox" value="${s}" id="segment-${s}" checked>
        <label class="form-check-label" for="segment-${s}">${s}</label>
      </div>
    `);
    });

    attachFilterHandlers();
}

/* ------------------ Attach filter handlers ------------------ */
function attachFilterHandlers() {
    filterBrandsDiv.off('change').on('change', '.filter-brands', function () {
        const val = $(this).val();
        if (this.checked) uniqPush(selectedFilters.brands, val);
        else selectedFilters.brands = removeVal(selectedFilters.brands, val);
        currentPage = 1;
        updateQueryString();
        getArticles(currentPage);
    });

    filterCategoryDiv.off('change').on('change', '.filter-category', function () {
        const val = $(this).val();
        if (this.checked) uniqPush(selectedFilters.categories, val);
        else selectedFilters.categories = removeVal(selectedFilters.categories, val);
        currentPage = 1;
        updateQueryString();
        getArticles(currentPage);
    });

    filterSegmentsDiv.off('change').on('change', '.filter-segments', function () {
        const val = $(this).val();
        if (this.checked) uniqPush(selectedFilters.segments, val);
        else selectedFilters.segments = removeVal(selectedFilters.segments, val);
        currentPage = 1;
        updateQueryString();
        getArticles(currentPage);
    });

    filterTypesDiv.off('change').on('change', '.filter-types', function () {
        const val = $(this).val();
        if (this.checked) uniqPush(selectedFilters.types, val);
        else selectedFilters.types = removeVal(selectedFilters.types, val);
        currentPage = 1;
        updateQueryString();
        getArticles(currentPage);
    });
}

/* ------------------ Toggles & Sort ------------------ */
$('#in-stock').on('change', function () {
    inStock = $(this).is(':checked') ? 1 : 0;
    currentPage = 1;
    updateQueryString();
    getArticles(currentPage);
});

$('#special-price').on('change', function () {
    specialPrice = $(this).is(':checked') ? 1 : 0;
    currentPage = 1;
    updateQueryString();
    getArticles(currentPage);
});

$('#sort-by').on('change', function () {
    sort = $(this).val() || "";
    currentPage = 1;
    updateQueryString();
    getArticles(currentPage);
});

$('#search-input').on('input', function () {
    search = $(this).val() || "";
    currentPage = 1;
    // add debounce if needed
});

/* ------------------ URL <-> State ------------------ */
function hydrateStateFromQuery() {
    const params = new URLSearchParams(window.location.search);

    if (params.has('search')) {
        search = params.get('search') || "";
        $('#search-input').val(search);
    }

    const qp = key => (params.get(key) || '').split(',').filter(Boolean);

    // 🔧 Ensure IDs are strings for strict includes() checks later
    selectedFilters.brands = qp('brand').map(String);
    selectedFilters.categories = qp('category');
    selectedFilters.segments = qp('segment');
    selectedFilters.types = qp('type').length ? qp('type') : qp('types');

    inStock = params.get('inStock') === '1' ? 1 : 0;
    specialPrice = params.get('specialPrice') === '1' ? 1 : 0;
    sort = params.get('sort') || "";
    const page = parseInt(params.get('page') || '1', 10);
    currentPage = isNaN(page) || page < 1 ? 1 : page;

    $('#in-stock').prop('checked', !!inStock);
    $('#special-price').prop('checked', !!specialPrice);
    $('#sort-by').val(sort);
}


function updateQueryString() {
    const url = new URL(window.location.href);
    const setOrDel = (key, arr) => {
        if (Array.isArray(arr) && arr.length) url.searchParams.set(key, arr.join(','));
        else url.searchParams.delete(key);
    };

    setOrDel('brand', selectedFilters.brands);
    setOrDel('category', selectedFilters.categories);
    setOrDel('segment', selectedFilters.segments);
    setOrDel('type', selectedFilters.types); // single param 'type'
    // if you prefer 'types', uncomment next line and comment the one above
    // setOrDel('types', selectedFilters.types);

    if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
    if (inStock) url.searchParams.set('inStock', '1'); else url.searchParams.delete('inStock');
    if (specialPrice) url.searchParams.set('specialPrice', '1'); else url.searchParams.delete('specialPrice');
    if (sort) url.searchParams.set('sort', sort); else url.searchParams.delete('sort');

    url.searchParams.set('page', String(currentPage));
    history.replaceState(null, '', url.toString());
}

/* ---------------- Notify Me (unchanged) ---------------- */
$(document).ready(function () {
    if ($('#notify-me-div').length) {
        let main = $('#notify-me-div');
        let notifyMe = $('#notifyMeCheckbox');
        let spinner = $('#notify-me-spinner-div');

        notifyMe.on('click', function () {
            if ($(this).is(":checked")) {
                main.hide();
                spinner.show();
                setStockAvailabilityNotification($(this).val(), res => {
                    main.show();
                    spinner.hide();
                    if (!res.error) {
                        toast.success(res.message);
                    } else {
                        toast.error(res.message);
                        notifyMe.prop('checked', false);
                    }
                });
            } else {
                main.show();
                spinner.hide();
            }
        });
    }
});

function setStockAvailabilityNotification(article_number, callback) {
    $.ajax({
        url: typeof route === 'function' ? route('shop/set-stock-availability-notification') : '/shop/set-stock-availability-notification',
        headers: (typeof headers === 'function') ? headers() : {},
        method: 'post',
        data: { article_number },
        success: function (response) { callback(response); },
        error(xhr) {
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            callback({ error: true, message: err.message });
        }
    });
}


function checkQueryParams() {
    // Get URLSearchParams object
    const params = new URLSearchParams(window.location.search);

    // Remove `page` if it exists
    params.delete('page');

    // Check if any other params remain
    if ([...params].length > 0) {
        true
    } else {
        false
    }
}
