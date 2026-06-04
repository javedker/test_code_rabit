$(document).ready(function () {

    $('#add-to-favorite').on('click', function () {
        let spinner = $('#add-to-favorite-spinner');
        let slug = $(this).data('slug')

        $('#add-to-favorite').hide()
        spinner.show()
        addToFavorites(slug, res => {
            spinner.hide()
            $('#remove-from-favorite').show()
            if (!res.error) {
                toast.success(res.message)
            } else {
                toast.error(res.message)
            }
        })

    })


    $('#remove-from-favorite').on('click', function () {
        let spinner = $('#add-to-favorite-spinner');
        let slug = $(this).data('slug')

        $('#remove-from-favorite').hide()
        spinner.show()
        removeItemFromFavorites(slug, res => {
            spinner.hide()
            $('#add-to-favorite').show()
            if (!res.error) {
                toast.success(res.message)
            } else {
                toast.error(res.message)
            }
        })

    })





    $(document).on('click', '.btn-remove-from-fav', function (e) {
        e.preventDefault();
        const button = $(this);
        const slug = button.data('slug');
        const row = $(`#fav-row-${slug}`);
        const spinner = row.find('.fav-remove-spinner');

        button.hide();
        spinner.show();

        removeItemFromFavorites(slug, res => {
            spinner.hide();
            if (!res.error) {
                row.remove();
                toast.success(res.message);

                // If no more rows, show empty message
                if ($('#main-fav-div').children('tr').length === 0) {
                    $('#main-fav-parent-div').hide();
                    $('#main-empty-fav-div').show();
                }
            } else {
                button.show();
                toast.error(res.message);
            }
        });
    });

})

function showMainFavPlaceholders() {
    const mainFavDiv = $('#main-fav-div');
    mainFavDiv.empty();

    let placeholderRows = '';
    for (let i = 0; i < 3; i++) {
        placeholderRows += `
        <tr class="placeholder-wave">
            <td>
                <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                    <div class="placeholder rounded" style="width: 50px; height: 50px;"></div>
                </div>
            </td>
            <td>
                <div class="placeholder col-8 d-block" style="height: 14px;"></div>
            </td>
            <td>
                <div class="placeholder col-4 d-block" style="height: 14px;"></div>
            </td>
            <td>
                <div class="placeholder col-6 d-block" style="height: 36px;"></div>
            </td>
            <td>
                <div class="placeholder col-4 d-block" style="height: 14px;"></div>
            </td>
        </tr>`;
    }

    mainFavDiv.html(placeholderRows);
}

function loadMainFavorites() {
    const mainFavDiv = $('#main-fav-div');
    const mainFavParentDiv = $('#main-fav-parent-div');
    const mainEmptyFavDiv = $('#main-empty-fav-div');

    showMainFavPlaceholders();

    getFavorites(res => {
        mainFavDiv.empty();
        if (!res.error && res.data.length) {
            mainFavParentDiv.show();
            mainEmptyFavDiv.hide();
            renderMainFavorites(res.data);
        } else {
            mainFavParentDiv.hide();
            mainEmptyFavDiv.show();
        }
    });
}

function renderMainFavorites(items) {
    const mainFavDiv = $('#main-fav-div');
    items.forEach(article => {
        const row = `
        <tr id="fav-row-${article.slug}">
            <td><img src="${route('media/' + setMedia(article.thumbnail))}" alt="${article.name}" width="80"></td>
            <td><strong>${article.name}</strong><br><small>${article.short_desc ?? ''}</small></td>
            <td>${formatPrice(article.price)} OMR</td>
            <td>
                <div class="d-flex flex-column gap-2 cart-action-wrapper">
                    <a href="#" class="tpproduct-details__cart btn-add-to-cart" data-slug="${article.slug}">
                        <button><i class="fal fa-shopping-cart"></i> Add To Cart</button>
                    </a>
                    <a class="tpproduct-details__cart btn-go-to-cart" href="${route('cart')}" style="display: none;">
                        <button><i class="fal fa-shopping-cart"></i> Go To Cart</button>
                    </a>
                    <div class="tpproduct-details__cart add-to-cart-spinner" style="display: none;">
                        <button>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Please Wait...
                        </button>
                    </div>
                </div>
            </td>
            <td class='text-center'>
                <div class="fav-remove-wrapper d-flex align-items-center text-center">
                    <a href="#" class="text-danger btn-remove-from-fav mx-5" data-slug="${article.slug}">
                        <i class="fal fa-trash fa-lg"></i>
                    </a>
                    <div class="spinner-border spinner-border-sm text-danger fav-remove-spinner" style="display: none;" role="status"></div>
                </div>
            </td>
        </tr>`;
        mainFavDiv.append(row);
    });
}

function getFavorites(callback) {
    $.ajax({
        url: route('favorites/get'),
        method: 'POST',
        headers: headers(),
        success(response) {
            callback(response);
        },
        error(xhr) {
            callback({ error: true, message: 'Failed to load favorites' });
        }
    });
}

$(document).ready(function () {
    loadMainFavorites();
});
