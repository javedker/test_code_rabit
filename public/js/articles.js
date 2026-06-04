// DOM elements
const articlesDiv = $('#articles-div');
const notArticle = $('#no-products');
const paginationDiv = $('#pagination');
const totalProductsDiv = $('#total-products');
const showArticle = $('#show-articles'); // <select id="show-articles">

// Keep ONLY these filter container refs (no filter logic below)
const filterCategoryDiv = $('#filter-category');
const filterTypesDiv = $('#filter-types');
const filterBrandsDiv = $('#filter-brands');
const filterSegmentsDiv = $('#filter-segments');

// State
let limit;
let currentPage = 1;
let firstLoad = true; // still used to guard initial render paths if needed
let inStock = 0;
let specialPrice = 0;
let sort = "";
let search = "";
let isQueryParams = checkQueryParams(); // works for any params excluding page
let brands = [];
let categories = [];
let segments = [];
let types = [];

$(document).ready(function () {
  // page size from select (fallback to 12)
  limit = parseInt(showArticle.val(), 10) || 12;

  // non-filter params (search/sort/inStock/specialPrice/page)
  hydrateStateFromQuery();

  // filters: read from URL -> check boxes -> fill arrays
  preloadFiltersFromURL();

  // wire up filter clicks (updates URL + refreshes list)
  attachFilterHandlers();

  // reload when user changes page-size
  showArticle.on('change', function () {
    limit = parseInt($(this).val(), 10) || limit;
    currentPage = 1;
    getArticles(currentPage);
  });

  // initial load using current page & preloaded filters
  getArticles(currentPage);
});

/* ----------------------- Helpers ----------------------- */
function safeText(text) {
  return String(text ?? '').replace(/[&<>"']/g, s => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[s]));
}
function buildProductUrl(slug) {
  try {
    if (typeof route === 'function') return route(`shop/details/${slug}`);
  } catch (e) { }
  return `/shop/details/${encodeURIComponent(slug)}`;
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


/* ------------------ Fetch & Render ------------------ */
function getArticles(page) {
  showArticlePlaceholders();

  const offset = (page - 1) * limit;

  $.ajax({
    url: route('shop/get'),
    method: 'get',
    data: {
      limit,
      offset,
      brands,
      categories,
      segments,
      types,
      specialPrice,
      inStock,
      sort,
      search
    },
    success: function (response) {
      if (!response.error) {
        totalProductsDiv.text(response.total ?? 0);
        notArticle.hide()
        paginationDiv.show()
        // (All filter rendering removed)

        renderArticles(response.data || []);
        renderPagination(response.total || 0, page);
        if (
          (brands && brands.length) ||
          (categories && categories.length) ||
          (segments && segments.length) ||
          (types && types.length) ||
          specialPrice ||
          inStock ||
          sort ||
          search
        ) {
          $("#remove-filters").removeClass("d-none");
        } else {
          $("#remove-filters").addClass("d-none");
        }
        updateQueryString(); // keep URL in sync after load
      } else {
        notArticle.show()
        paginationDiv.hide()
        totalProductsDiv.html('Not Product Found')
        articlesDiv.empty();
      }
    },
    error: function (xhr) {
      articlesDiv.empty();
      notArticle.show()
      paginationDiv.hide()
      totalProductsDiv.html('Not Product Found')
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
    let favoritesFilled = `<a href=""  class="position-absolute top-0 end-0 mx-2 mt-2 set-favorite" title='Add to Favorite' id="favorite-empty-heart-${article.slug}" data-slug="${article.slug}" style='display:none'>
                <i class='far fa-heart text-primary'></i>
              </a>
              <a href=""  class="position-absolute top-0 end-0 mx-2 mt-2 remove-favorite"  title='Remove from Favorite' id="favorite-filled-heart-${article.slug}"  data-slug="${article.slug}" >
                <i class='fas fa-heart text-primary'></i>
              </a>`;
    let favoritesEmpty = `<a href=""  class="position-absolute top-0 end-0 mx-2 mt-2 set-favorite"  title='Add to Favorite' id="favorite-empty-heart-${article.slug}"   data-slug="${article.slug}">
                <i class='far fa-heart text-primary'></i>
              </a>
              <a href=""  class="position-absolute top-0 end-0 mx-2 mt-2 remove-favorite"  title='Remove from Favorite' style='display:none' id="favorite-filled-heart-${article.slug}"  data-slug="${article.slug}">
                <i class='fas fa-heart text-primary'></i>
              </a>`;

    const html = `
      <div class="col">
        <div class="tpproduct tpproductitem mb-15 p-relative">
          <div class="tpproduct__thumb">
            <div class="tpproduct__thumbitem p-relative">
              ${article.isFavorite == true ? favoritesFilled : favoritesEmpty}
              <a href="${slug}">
                <img src="${route('media/' + setMedia(article.thumbnail))}" alt="product-thumb">
              </a>
            </div>
          </div>
          <div class="tpproduct__content-area text-start">
            <b>${brand}</b>
            <h3 class="tpproduct__title mb-5"><a href="${slug}">${name}</a></h3>
             ${article?.discount
        ? `<span class="sale-price badge rounded-0 bg-danger-2">${Math.abs(article.discount)}%</span>`
        : ''}
            <div class="tpproduct__priceinfo p-relative">
                            ${article?.discount
        ? `<del>${formatPrice(article.base_price * 1.05)}</del>`
        : ''}
              <b class="text-primary">
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

/* ------------------ Toggles & Sort (kept) ------------------ */
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

// ===== Read from URL on load =====
function preloadFiltersFromURL() {
  const params = new URLSearchParams(window.location.search);
  const arr = k => (params.get(k) || '').split(',').filter(Boolean);

  brands = arr('brand');
  categories = arr('category');
  segments = arr('segment');
  types = arr('type');

  // Mark checkboxes
  $('.filter-brands').each(function () {
    if (brands.includes($(this).val())) $(this).prop('checked', true);
  });
  $('.filter-category').each(function () {
    if (categories.includes($(this).val())) $(this).prop('checked', true);
  });
  $('.filter-segments').each(function () {
    if (segments.includes($(this).val())) $(this).prop('checked', true);
  });
  $('.filter-types').each(function () {
    if (types.includes($(this).val())) $(this).prop('checked', true);
  });
}

// ===== Write to URL whenever filters change =====
function updateFilterQueryString() {
  const url = new URL(window.location.href);
  const setOrDel = (key, arr) => {
    if (Array.isArray(arr) && arr.length) url.searchParams.set(key, arr.join(','));
    else url.searchParams.delete(key);
  };

  setOrDel('brand', brands);
  setOrDel('category', categories);
  setOrDel('segment', segments);
  setOrDel('type', types);

  // reset to first page when filters change
  url.searchParams.set('page', '1');

  // push into address bar without reloading
  history.replaceState(null, '', url.toString());
}

// ===== Handlers (add/remove + URL + refresh) =====
function attachFilterHandlers() {
  $('.filter-brands').on('click', function () {
    const val = $(this).val();
    if (this.checked) { if (!brands.includes(val)) brands.push(val); }
    else { brands = brands.filter(v => v !== val); }
    updateFilterQueryString();
    getArticles(1);
  });

  $('.filter-category').on('click', function () {
    const val = $(this).val();
    if (this.checked) { if (!categories.includes(val)) categories.push(val); }
    else { categories = categories.filter(v => v !== val); }
    updateFilterQueryString();
    getArticles(1);
  });

  $('.filter-segments').on('click', function () {
    const val = $(this).val();
    if (this.checked) { if (!segments.includes(val)) segments.push(val); }
    else { segments = segments.filter(v => v !== val); }
    updateFilterQueryString();
    getArticles(1);
  });

  $('.filter-types').on('click', function () {
    const val = $(this).val();
    if (this.checked) { if (!types.includes(val)) types.push(val); }
    else { types = types.filter(v => v !== val); }
    updateFilterQueryString();
    getArticles(1);
  });
}

















/* ------------------ URL <-> State (filters removed) ------------------ */
function hydrateStateFromQuery() {
  const params = new URLSearchParams(window.location.search);

  if (params.has('search')) {
    search = params.get('search') || "";
    $('#search-input').val(search);
  }

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

  if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
  if (inStock) url.searchParams.set('inStock', '1'); else url.searchParams.delete('inStock');
  if (specialPrice) url.searchParams.set('specialPrice', '1'); else url.searchParams.delete('specialPrice');
  if (sort) url.searchParams.set('sort', sort); else url.searchParams.delete('sort');

  url.searchParams.set('page', String(currentPage));
  history.replaceState(null, '', url.toString());
}

/* ---------------- Notify Me (kept) ---------------- */
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

/* ---------------- Query param presence (excluding page) ---------------- */
function checkQueryParams() {
  const params = new URLSearchParams(window.location.search);
  params.delete('page');
  return [...params].length > 0;
}




$('#articles-div').on('click', '.set-favorite', function (e) {
  e.preventDefault()
  let slug = $(this).data('slug')
  let favoriteEmpty = $('#favorite-empty-heart-' + slug)
  let favoriteFilled = $('#favorite-filled-heart-' + slug)
  addToFavorites(slug, res => {
    if (!res.error) {
      favoriteEmpty.hide()
      favoriteFilled.show()
      toast.success(res.message)
    } else {
      favoriteEmpty.show()
      favoriteFilled.hide()
      toast.error(res.message)
    }
  })
})
$('#articles-div').on('click', '.remove-favorite', function (e) {
  e.preventDefault()
  let slug = $(this).data('slug')
  let favoriteEmpty = $('#favorite-empty-heart-' + slug)
  let favoriteFilled = $('#favorite-filled-heart-' + slug)
  removeItemFromFavorites(slug, res => {
    if (!res.error) {
      favoriteEmpty.show()
      favoriteFilled.hide()
      toast.success(res.message)
    } else {
      favoriteEmpty.hide()
      favoriteFilled.show()
      toast.error(res.message)
    }
  })
})