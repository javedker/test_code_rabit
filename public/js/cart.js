let addToCartSpinnerDetails = $('#add-to-card-spinner-details');
let goToCartDetails = $('#got-to-cart-details');
let mainCartDiv = $('#main-cart-div')
let mainEmptyCartDiv = $('#main-empty-cart-div')
let mainCartParentDiv = $('#main-cart-parent-div')
if (mainEmptyCartDiv.length) {
    mainEmptyCartDiv.hide()
}

$('#add-to-cart').on('click', function () {
    $(this).hide()
    addToCartSpinnerDetails.show()
    let slug = $(this).data('slug')
    set(slug, 1, res => {
        addToCartSpinnerDetails.hide()
        if (!res.error) {
            cartCount()
            goToCartDetails.show()
            toast.success(res.message)
        } else {
            $('#add-to-cart').show()
            toast.error(res.message)
        }
    })
})

function set(slug, qty, callback) {
    $.ajax({
        url: route('cart/set'),
        method: 'POST',
        headers: headers(),
        data: {
            slug: slug,
            qty: qty
        },
        success(response) {
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

$(document).ready(function () {
    if (mainCartDiv.length) {
        loadMainCart()
    }
})


function loadMainCart() {
    showMainSpinner();
    getCart(res => {
        mainCartDiv.empty()
        hideMainSpinner()
        if (!res.error) {
            mainCartParentDiv.show()
            mainEmptyCartDiv.hide()
            renderMainCart(res.data)
            if (!res.other.isProcess) {
                $('#checkout-proceed-button').hide()
                $('#checkout-proceed-button-error').show()
            } else {
                $('#checkout-proceed-button').show()
                $('#checkout-proceed-button-error').hide()
            }
        } else {
            mainCartParentDiv.hide()
            mainEmptyCartDiv.show()
        }

    })
}

function renderMainCart(items) {

    items.forEach(item => {
        let error = item.error ? item.error : "";

        let itemDiv =
            `
            <tr>
                <td class="product-thumbnail"><img src="${route('media/' + setMedia(item.thumbnail))}" width="50" /></td>
                <td>
                    <div class="text-start">
                        <a href="${route('shop/details/' + item.slug)}"><b>${item.name}</b></a>
                        <br>
                        Brand: ${item.brand_name} <br>
                        Model No.: ${item.model_no} <br>
                        Article No.: ${item.sap_article_number}
                        ${error ? `<br> <span style='color:red'>${error}<br> Remove this item to proceed</span> ` : ""}
                        <!-- Unit price moved here (below Article No.) -->
                            ${item.discount > 0 ? `<br><span class="sale-price badge rounded-0 bg-danger-2">${item.discount}% off</span>` : ""}
                        <div class="tpproduct-details__price mt-1">
                           ${item.discount > 0 ? `<del  style='font-size:12px;margin: 0px !important;'>${formatPrice(item.base_price)}</del>` : ""}
                            <span style='font-size:16px;margin: 0px !important;'> ${formatPrice(item.price)} </span> (Incl. VAT) (Supply Only)
                        </div>

                    </div>
                </td>
                <!-- Removed the separate unit price <td> column -->
                <td>
                    <div class="tpproduct-details__quantity" id='qty-div-${item.slug}'>
                        <a class="cart-minus" onClick="setQty(event,this)" data-slug="${item.slug}" data-type='sub'><i class="far fa-minus"></i></a>
                        <input class="tp-cart-input" id="qty-input-${item.slug}" type="text" value="${item.qty}" disabled>
                        <a class="cart-plus" onClick="setQty(event,this)" data-slug="${item.slug}" data-type='add'><i class="far fa-plus"></i></a>
                    </div>
                    <div class="qty-spinner-div-${item.slug}" style="display:none;">
                        <div class="spinner-border text-primary spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </td>
                <td id="item-subtotal-${item.slug}" data-price="${item.price}">${formatPrice(parseFloat(item.price) * item.qty)} </td>
                <td>
                    <a onClick="removeItem(event,this)" data-slug="${item.slug}"><i class='fal fa-trash text-danger'></i></a>
                    <div class="remove-item-spinner-div-${item.slug}" style="display:none;">
                        <div class="spinner-border text-primary spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </td>
            </tr>
    `;

        mainCartDiv.append(itemDiv)
    });
}

function setQty(event, element) {
    event.preventDefault()
    let slug = $(element).data('slug')
    let type = $(element).data('type')
    let input = $(`#qty-input-${slug}`)
    let qty = type == 'sub' ? parseInt(input.val()) - 1 : parseInt(input.val()) + 1;
    let spinner = $(`.qty-spinner-div-${slug}`)
    let parentDiv = $(`#qty-div-${slug}`)
    let itemSubtotal = $(`#item-subtotal-${slug}`)
    spinner.show()
    parentDiv.hide()
    set(slug, qty, res => {
        spinner.hide()
        parentDiv.show()
        if (!res.error) {
            let price = parseFloat(itemSubtotal.data('price'))
            itemSubtotal.html(formatPrice(price * res.data.qty))
            input.val(res.data.qty)
        } else {
            toast.error(res.message)
        }
    })
}
function removeItem(event, element) {
    event.preventDefault()
    let slug = $(element).data('slug')
    let spinner = $(`.remove-item-spinner-div-${slug}`);
    spinner.show()
    $(element).hide()
    removeFromCart(slug, res => {
        spinner.hide()
        $(element).show()
        if (!res.error) {
            loadMainCart()
            cartCount()
            toast.success(res.message)
        } else {
            toast.error(res.message)
        }
    })

}

// Only run this logic on favorites page
if ($('#main-fav-div').length) {
    $(document).on('click', '.btn-add-to-cart', function (e) {
        e.preventDefault();

        const button = $(this);
        const slug = button.data('slug');

        const container = button.closest('td');
        const spinner = container.find('.add-to-cart-spinner');
        const goToCartBtn = container.find('.btn-go-to-cart');

        button.hide();
        spinner.show();
        goToCartBtn.hide();
        set(slug, 1, res => {

            if (!res.error) {
                removeItemFromFavorites(slug, res => {
                    spinner.hide();
                    cartCount();
                    goToCartBtn.show();
                    toast.success(res.message);
                })
            } else {
                button.show();
                spinner.hide()
                goToCartBtn.hide();
                toast.error(res.message);
            }
        });
    });
}
if ($('#main-compare-div').length) {
    $(document).on('click', '.btn-add-to-cart', function (e) {
        e.preventDefault();

        const button = $(this);
        const slug = button.data('slug');

        const spinner = $(`#compare-spinner-${slug}`);
        const goToCartBtn = $(`#compare-go-to-cart-${slug}`);

        button.hide();
        spinner.show();
        goToCartBtn.hide();
        set(slug, 1, res => {
            if (!res.error) {
                spinner.hide();
                cartCount();
                goToCartBtn.show();
                toast.success(res.message);
            } else {
                button.show();
                spinner.hide()
                goToCartBtn.hide();
                toast.error(res.message);
            }
        });
    });
}


$('#main-clear-cart').on('click', function (e) {
    e.preventDefault();

    const button = $(this);
    const spinner = $(`#main-clear-cart-spinner`);


    button.hide();
    spinner.show();
    clearCart(res => {
        if (!res.error) {
            spinner.hide();
            button.show();
            cartCount();
            loadMainCart()
            toast.success(res.message);
        } else {
            button.show();
            spinner.hide()
            toast.error(res.message);
        }
    });
})