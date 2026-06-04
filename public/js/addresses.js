
let addAddressModal = $('#addAddressModal')

$(document).ready(function () {
    // Initialize niceSelect once
    $('#citySelect').niceSelect();

    if (addAddressModal.length) {
        cities(res => {
            let citySelect = $('#citySelect');
            citySelect.empty();

            if (!res.error) {
                // Add default option
                citySelect.append('<option value="">Select city</option>');

                // Append cities from AJAX
                res.data.forEach(city => {
                    let option = `<option value="${city.id}">${city.name}</option>`;
                    citySelect.append(option);
                });
            } else {
                citySelect.append('<option value="">No Cities Found</option>');
            }

            // Refresh niceSelect UI
            citySelect.niceSelect('update');
        });
    }

    if ($('#account-addresses').length) {
        renderAddresses()
    }

});

function renderAddresses() {
    getAddresses(function (res) {
        const $list = $('#addresses-list');
        $list.empty();

        if (res.error) {
            $list.html('<div class="alert alert-danger">' + (res.message || 'Failed to load addresses') + '</div>');
            return;
        }
        const items = Array.isArray(res.data) ? res.data : [];
        if (!items.length) {
            $list.html('<div class="alert alert-info">No addresses found</div>');
            return;
        }

        // Is there a primary?
        const primaryIndex = items.findIndex(a => String(a.is_primary) === '1' || a.is_primary === 1 || a.isPrimary === true);
        const firstCheckedIndex = primaryIndex >= 0 ? primaryIndex : 0;

        items.forEach(function (addr, i) {
            const bgClass = i % 2 === 0 ? 'bg-pink' : 'bg-cream';
            const isPrimary = String(addr.is_primary) === '1' || addr.is_primary === 1 || addr.isPrimary === true;
            const isChecked = i === firstCheckedIndex ? 'checked' : '';
            const selectedClass = isChecked ? 'selected' : '';
            const phoneHtml = addr.alternative_number ? `<div class="addr-phone">Phone: ${addr.alternative_number}</div>` : '';

            const card = `
        <div class="address-card ${bgClass} ${selectedClass}">
          <label class="address-radio d-block m-0 w-100 addr-radio">
            <input
              type="radio"
              name="selected_address_id"
              value="${addr.id}"
              onclick="selectAddress(this)"
              ${isChecked}
            >
            <div class="addr-info pe-5">
              <div class="d-flex align-items-center gap-2">
                ${isPrimary ? '<i class="fas fa-home text-primary" title="Primary Address"></i>' : ''}
                <div class="addr-name">${addr.name ?? ''}</div>
              </div>
              <div class="addr-line">
                ${[addr.address, addr.street, addr.city_name, addr.postal_code].filter(Boolean).join(', ')}
              </div>
              ${phoneHtml}
            </div>
          </label>

          ${!isPrimary ? `
            <div class="addr-actions">
              <button type="button"
                class="btn btn-light set-primary-address"
                data-id="${addr.id}"
                title="Set as Default">
                <i class="fas fa-map-marker-alt text-primary"></i> Set Primary Address
              </button>
            </div>` : ''}
        </div>
      `;
            $list.append(card);
        });
    });
}

$('#addresses-list').on('click', '.set-primary-address', function () {
    const id = $(this).data('id');
    const $card = $(this).closest('.address-card');
    const $list = $('#addresses-list');
    setPrimaryAddress(id, res => {
        if (!res.error) {
            // 1) Move visual card to top with animation
            moveAddressCardToTop($card);

            // 2) Update primary UI bits locally
            //    a) Remove primary icon from all, show "Set Primary" on others
            $list.find('.address-card .fa-home').closest('.d-flex').find('.fa-home').remove();
            $list.find('.set-primary-address').closest('.addr-actions').show();

            //    b) Add primary icon on this card and hide its Set button
            const $iconsRow = $card.find('.d-flex.align-items-center.gap-2').first();
            if ($iconsRow.find('.fa-home').length === 0) {
                $iconsRow.prepend('<i class="fas fa-home text-primary" title="Primary Address"></i>');
            }
            $card.find('.addr-actions').hide();

            // 3) Radio: mark this as selected
            $list.find('input[name="selected_address_id"]').prop('checked', false);
            $card.find('input[name="selected_address_id"]').prop('checked', true);

            // 4) Optional: after the animation, re-sync from server for absolute truth
            setTimeout(() => renderAddresses(), 600);

            toast.success(res.message);
        } else {
            toast.error(res.message);
        }
    })
})

function cities(callback) {
    $.ajax({
        url: route('addresses/cities'),
        method: 'post',
        headers: headers(),
        success: function (response) {
            callback(response);
        },
        error: function (xhr) {
            let err = JSON.parse(xhr.responseText);
            callback({ error: true, message: err.message });
        },
    });
}
$('#store-address').on('submit', function (e) {
    e.preventDefault();

    const $form = $(this);
    const form = this;

    // build FormData directly from the <form> element
    const formData = new FormData(form);

    // disable the button while we wait
    const $btn = $form.find('button[type="submit"]');
    $btn.prop('disabled', true).append(' <i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: route('addresses/store'),
        method: 'POST',
        headers: headers(),
        data: formData,
        processData: false,  // tell jQuery not to transform the data
        contentType: false,  // tell jQuery not to set contentType
        success: function (res) {
            if (!res.error) {
                $('#store-address').closest('.modal').modal('hide');
                $btn.prop('disabled', false).find('.fa-spinner').remove();
                toast.success(res.message || 'Address saved.');

                if ($('#checkout-div').length > 0) {
                    $('#add-new-address-div').hide()
                    $('.addresses-wrapper').show()
                    renderAddressPlaceholders();
                    renderAddressesCheckout(res.data);
                } else {
                    renderAddresses();
                }

            } else {
                toast.error(res.message || 'Save failed.');
            }
        },
        error: function (xhr) {
            let err = xhr.responseJSON;
            if (xhr.status === 422 && err.errors) {
                // validation
                Object.values(err.errors).flat().forEach(msg => toast.error(msg));
            } else {
                toast.error(err.message || 'Server error');
            }
        },
        complete: function () {
            // re-enable button & remove spinner
            $btn.prop('disabled', false).find('.fa-spinner').remove();
        }
    });
})


function setPrimaryAddress(id, callback) {
    $.ajax({
        url: route('addresses/set-primary'),
        method: 'post',
        headers: headers(),
        data: {
            id: id
        },
        success: function (response) {
            callback(response);
        },
        error: function (xhr) {
            let err = JSON.parse(xhr.responseText);
            callback({ error: true, message: err.message });
        },
    });
}

function moveAddressCardToTop($card) {
    const $list = $('#addresses-list');

    // Mark moving + small visual cue
    $card.addClass('moving');

    // Fade-slide out a bit, then move to top and fade-slide in
    $card.animate({ opacity: 0, height: 'toggle', marginTop: 0, marginBottom: 0 }, 220, function () {
        // Reorder in DOM
        $card.prependTo($list);

        // Ensure the container scrolls to top
        $list.scrollTop(0);

        // Show it again with a highlight
        $card
            .css({ opacity: 0 })
            .show()
            .animate({ opacity: 1 }, 220, function () {
                $card.removeClass('moving').addClass('highlight');
                setTimeout(() => $card.removeClass('highlight'), 1000);
            });
    });
}