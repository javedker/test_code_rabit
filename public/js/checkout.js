// Element refs & state
let addressesList = $('.addresses-list');
let addressesWrapper = $('.addresses-wrapper');
let addNewAddressDiv = $('#add-new-address-div');
let subTotalDiv = $('#subTotal');
let totalTaxDiv = $('#totalTax');
let finalTotalDiv = $('#finalTotal');
let placeOrderBtnDiv = $('#place-order-btn-div');
let placeOrderSpinner = $('#place-order-spinner');
let placeOrderSpinnerText = $('#place-order-spinner-text');
let orderId = $('#order-id');
let isTerms = false;

let finalTotal = 0;
let tax = 0;
let selectedAddress = 0;
let isProccessCheckout = false;
// On DOM ready
$(document).ready(function () {
    // Show placeholders

    showMainSpinner();

    // Fetch initial data
    get(res => {
        hideMainSpinner()
        if (res.error) {
            // Hide checkout UI, show error panel
            $('.checkout-area').hide();
            $('#checkout-error-message').text(res.message);
            $('#checkout-error').show();
            return;
        }

        if (res.addresses.length > 0) {
            addressesWrapper.show();
            addNewAddressDiv.hide();
            renderAddressesCheckout(res.addresses);

        } else {
            addressesWrapper.hide();
            addNewAddressDiv.show();
        }
        finalTotalDiv.html(formatPrice(res.final_total));
        subTotalDiv.html(formatPrice(res.sub_total));
        totalTaxDiv.html(formatPrice(res.total));
        finalTotal = res.final_total;
        tax = res.total;

        if (!res.other.isCheckout) {
            $('#place-order-btn-error-div').show()
            $('#place-order-btn-parent-div').empty()
            isProccessCheckout = false;
        } else {
            isProccessCheckout = true;
            $('#place-order-btn-error-div').hide()
            $('#place-order-btn-parent-div').show()
        }
        renderCheckoutOrderSummary(res.data.items);
    });
});

// GET helper
function get(callback) {
    $.ajax({
        url: route('checkout/get'),
        method: 'post',
        headers: headers(),
        success: callback,
        error: function (xhr) {
            let err = JSON.parse(xhr.responseText);
            callback({ error: true, message: err.message });
        }
    });
}

// Render addresses
function renderAddressesCheckout(addresses) {
    addressesList.empty();

    // Check if any address has isPrimary
    const hasPrimary = addresses.some(addr => addr.is_primary);

    addresses.forEach((addr, index) => {
        // If address is primary → checked
        // Else if no primary and this is the first → checked
        const isChecked = (addr.is_primary || (!hasPrimary && index === 0)) ? 'checked' : '';
        if (isChecked) {
            selectedAddress = addr.id;
        }
        addressesList.append(`
          <div class="address-card">
            <label class="address-radio d-flex align-items-center">
              <input
                type="radio"
                name="selected_address_id"
                onClick="selectAddress(this)"
                value="${addr.id}"
                ${isChecked}
              >
              <b class="addr-name">${addr.name}</b> <small class="text-muted mx-1">${isChecked ? " (Primary) " : ""}</small>
            </label>

            <div class="addr-info ms-4">
              <div class="addr-line">
                ${addr.address}, ${addr.street}, ${addr.city}, ${addr.postal_code}
              </div>
              ${addr.alternative_number ? `<div class="addr-phone">Phone: ${addr.alternative_number}</div>` : ''}
            </div>
          </div>
        `);
    });
}


// Render order summary
function renderCheckoutOrderSummary(items) {
    const $tbody = $('#order-summary').empty();
    Object.values(items).forEach(item => {
        const total = item.qty * parseFloat(item.price) + parseFloat(item.tax);
        let error = item.error ? item.error : "";
        $tbody.append(`
            <tr>
              <td>${item.article}</td>
              <td>${item.name}${error ? `<br> <span style='color:red'>${error}</span> ` : ""}</td>
              <td class="text-end">${item.qty}</td>
              <td class="text-end">${formatPrice(item.qty * parseFloat(item.price))}</td>
              <td class="text-end">${formatPrice(item.tax)}</td>
              <td class="text-end">${formatPrice(total)}</td>
            </tr>
            
        `);
    });
}

// Placeholders
function renderAddressPlaceholders(count = 3) {
    addressesList.empty();
    for (let i = 0; i < count; i++) {
        addressesList.append(`
          <div class="address-card placeholder-glow p-3 border rounded mb-2">
            <div class="mb-2"><span class="placeholder col-6"></span></div>
            <div class="mb-2"><span class="placeholder col-8"></span></div>
            <div><span class="placeholder col-4"></span></div>
          </div>
        `);
    }
}

function renderOrderSummaryPlaceholders(rows = 3) {
    const $tbody = $('#order-summary').empty();
    for (let i = 0; i < rows; i++) {
        $tbody.append(`
          <tr class="placeholder-glow">
            <td><span class="placeholder col-6"></span></td>
            <td><span class="placeholder col-7"></span></td>
            <td class="text-end"><span class="placeholder col-4"></span></td>
            <td class="text-end"><span class="placeholder col-5"></span></td>
            <td class="text-end"><span class="placeholder col-4"></span></td>
            <td class="text-end"><span class="placeholder col-5"></span></td>
          </tr>
        `);
    }
}

// Place order AJAX helper
function placeOrder(callback, addressId, taxAmount) {
    $.ajax({
        url: route('checkout/place-order'),
        headers: headers(),
        method: 'POST',
        data: { addressId, tax: taxAmount },
        success: callback,
        error: xhr => {
            let err = JSON.parse(xhr.responseText);
            callback({ error: true, message: err.message });
        }
    });
}

function selectAddress(el) {
    selectedAddress = +$(el).val();
}

// Button-click handler
$('#place-order-btn').on('click', function () {
    if (!selectedAddress) {
        toast.error('Please Select or Add New Delivery Address');
        return;
    }

    if (!isProccessCheckout) {
        toast.error('Unable to proceed — please review your cart items');
        return;
    }
    if (!isTerms) {
        toast.error('Please agree to the terms and conditions');
        return;
    }

    placeOrderBtnDiv.hide();
    placeOrderSpinnerText.html('Placing Order...');
    placeOrderSpinner.show();

    placeOrder(res => {
        if (!res.error) {
            toast.success(res.message);
            setTimeout(() => {
                location.href = res.data.redirect
            }, 2000)
        } else {
            toast.error(res.message);
        }
        placeOrderBtnDiv.show();
        placeOrderSpinner.hide();
    }, selectedAddress, tax);
});

$('#is_terms_and_condition').on('click', function () {
    if ($(this).is(':checked')) {
        isTerms = true;
    } else {
        isTerms = false
    }
})