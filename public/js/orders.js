// custom light badges
const statusMap = {
  order_placed: '<span class="badge-soft badge-soft-info">Order Placed</span>',
  confirmed: '<span class="badge-soft badge-soft-warning">Confirmed</span>',
  ready_for_pickup: '<span class="badge-soft badge-soft-secondary">Ready For Pickup</span>',
  out_for_delivery: '<span class="badge-soft badge-soft-primary">Out For Delivery</span>',
  delivered: '<span class="badge-soft badge-soft-success">Delivered</span>',
  cancelled: '<span class="badge-soft badge-soft-danger">Cancelled</span>',
  returned: '<span class="badge-soft badge-soft-danger">Returned</span>',
  awaiting_payment: '<span class="badge-soft badge-soft-dark">Payment Pending</span>',
  payment_success: '<span class="badge-soft badge-soft-dark">Payment Success</span>'
};

let ordersListingDiv = $('#orders-listing');
let ordersPlaceholder = $('#orders-placeholder');
let ordersContainer = $('#orders-container');
let ordersEmptyTbody = $('#orders-empty');
let ordersErrorDiv = $('#orders-error');

$(document).ready(function () {
  if (!ordersListingDiv.length) return;

  // initial state
  ordersErrorDiv.hide();
  ordersContainer.hide();
  ordersEmptyTbody.hide();
  ordersPlaceholder.show();

  // fetch
  getOrders(res => {
    ordersPlaceholder.hide();

    if (!res.error) {
      renderOrderList(res.data);
    } else {
      ordersListingDiv.hide();
      ordersErrorDiv.show();
      $('#orders-error-message').html(res.message);
    }
  });
});

function getOrders(callback) {
  $.ajax({
    url: route('account/orders/get'),
    method: 'POST',
    headers: headers(),
    dataType: 'json',
    success: callback,
    error(xhr) {
      let err = JSON.parse(xhr.responseText);
      callback({ error: true, message: err.message });
    }
  });
}

function renderOrderList(items) {
  ordersContainer.empty();
  ordersErrorDiv.hide();
  ordersListingDiv.show();

  if (!items.length) {
    ordersEmptyTbody.show();
    return;
  }

  ordersEmptyTbody.hide();
  ordersContainer.show();

  items.forEach(item => {
    const badge = statusMap[item.status] || `<span class="badge " style='background:#ff9999;'>${item.status}</span>`;
    const date = new Date(item.created_at).toLocaleDateString(undefined, {
      year: 'numeric', month: 'short', day: 'numeric'
    });
    const total = parseFloat(item.final_total).toLocaleString(undefined, {
      minimumFractionDigits: 2, maximumFractionDigits: 2
    });
    const detailsUrl = route(`account/orders/view/${item.ecommerce_order_id}`);

    ordersContainer.append(`
            <tr>
              <td>${item.ecommerce_order_id}</td>
              <td>${date}</td>
              <td>${total}</td>
              <td>${badge}</td>
          <td class="text-center">
            <a href="${detailsUrl}"
              class="btn-soft btn-soft-gray btn-sm"
              title="View details">
              <i class="fas fa-eye"></i>
            </a>
          </td>
            </tr>
        `);
  });
}
