// security.js

let resetPwdSpinner = $('#reset-pwd-spinner');
let resetPwdSubmit  = $('#reset-pwd-submit');

const $devicesList  = $('#devices-list');   // card list container (new UI)
const $devicesTbody = $('#devices-tbody');  // table body (fallback/legacy)
const $revokeOthers = $('#revoke-others');

/* ---------- helpers ---------- */
function getCookie(name) {
  const m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([$?*|{}\]\\\/+^])/g,'\\$1') + '=([^;]*)'));
  return m ? decodeURIComponent(m[1]) : '';
}

function timeAgo(ts){
  if(!ts) return '—';
  const t = new Date(String(ts).replace(' ', 'T'));
  const diff = (Date.now() - t.getTime())/1000;
  if (diff < 60)   return 'just now';
  if (diff < 3600) return Math.floor(diff/60) + ' min ago';
  if (diff < 86400)return Math.floor(diff/3600) + ' hr ago';
  return t.toLocaleDateString() + ' ' + t.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
}

function isActive(ts){
  if(!ts) return false;
  const t = new Date(String(ts).replace(' ', 'T')).getTime();
  return (Date.now() - t) < (7*24*3600*1000); // active if seen within 7 days
}

/* ---------- card UI builders ---------- */
function deviceCardHtml(d, currentUid){
  const active = isActive(d.last_seen_at);
  const statusClass = active ? 'active' : 'inactive';
  const statusText  = active ? 'Active' : 'Inactive';
  const thisBadge   = (d.device_uid === currentUid)
    ? `<span class="badge bg-success-subtle text-success border ms-2">This device</span>` : '';
  const lastSeen  = timeAgo(d.last_seen_at);
  const lastLogin = timeAgo(d.last_login_at);
  const revokeBtn = (d.device_uid === currentUid)
    ? `<button class="btn btn-sm btn-outline-secondary" disabled>Current</button>`
    : `<button class="btn btn-sm btn-outline-danger btn-revoke revoke-device">Revoke</button>`;

  return `
    <div class="device-item" data-id="${d.id}" data-uid="${d.device_uid}">
      <div class="cell-status">
        <span class="status-pill ${statusClass}"><span class="dot"></span> ${statusText}</span>
      </div>
      <div class="cell-device">
        <div class="dev-name">${(d.device_name || 'Unknown device')} ${thisBadge}</div>
        <div class="dev-meta">${(d.browser || '—')} • ${(d.os || '—')} • IP ${d.ip_address || '—'}</div>
      </div>
      <div class="cell-price">
        <div class="amount">${lastSeen}</div>
        <div class="per">last login: ${lastLogin}</div>
      </div>
      <div class="cell-actions">
        <span class="icon i-info" title="${(d.user_agent || '')}"></span>
        ${revokeBtn}
        <span class="icon i-chevron"></span>
      </div>
    </div>`;
}

/* ---------- table (fallback) builder ---------- */
function deviceRowHtml(d, currentUid) {
  const isCurrent = d.device_uid === currentUid;
  return `
  <tr data-id="${d.id}" data-uid="${d.device_uid}">
    <td>
      <div class="fw-semibold">${(d.device_name || 'Unknown device')}</div>
      ${isCurrent ? `<span class="badge bg-success-subtle text-success border">This device</span>` : ``}
      ${d.token_id ? `<div class="text-muted small">Token: #${d.token_id}</div>` : ``}
    </td>
    <td>
      <div class="fw-semibold">${d.browser || '—'}</div>
      <div class="text-muted small">${d.os || '—'}</div>
    </td>
    <td class="text-muted">${d.ip_address || '—'}</td>
    <td class="text-muted">${d.last_login_at || '—'}</td>
    <td class="text-muted">${d.last_seen_at || '—'}</td>
    <td class="text-end">
      ${isCurrent
          ? `<button class="btn btn-sm btn-outline-secondary" disabled>Current</button>`
          : `<button class="btn btn-sm btn-outline-danger revoke-device">Revoke</button>`
      }
    </td>
  </tr>`;
}

/* ---------- render ---------- */
function renderDevices(list){
  const currentUid = getCookie('device_uid');

  if ($devicesList.length) {
    if (!Array.isArray(list) || list.length === 0) {
      $devicesList.html(`<div class="text-center text-muted py-4">No devices found</div>`);
      return;
    }
    $devicesList.html(list.map(d => deviceCardHtml(d, currentUid)).join(''));
    return;
  }

  // fallback: table
  if (!Array.isArray(list) || list.length === 0) {
    $devicesTbody.html(`<tr><td colspan="6" class="text-center text-muted py-4">No devices found</td></tr>`);
    return;
  }
  const html = list.map(d => deviceRowHtml(d, currentUid)).join('');
  $devicesTbody.html(html);
}

/* ---------- load devices ---------- */
function showLoading() {
  if ($devicesList.length) {
    $devicesList.html(`
      <div class="device-item skeleton">
        <div class="cell-status"><span class="dot"></span> Loading…</div>
        <div class="cell-device">
          <div class="dev-name shimmer">&nbsp;</div>
          <div class="dev-meta shimmer w-50">&nbsp;</div>
        </div>
        <div class="cell-price">
          <div class="amount shimmer w-50">&nbsp;</div>
          <div class="per shimmer w-25">&nbsp;</div>
        </div>
        <div class="cell-actions">
          <span class="icon i-info"></span>
          <span class="icon i-gear"></span>
          <span class="icon i-chevron"></span>
        </div>
      </div>`);
  } else {
    $devicesTbody.html(`<tr><td colspan="6" class="text-center text-muted py-4">Loading…</td></tr>`);
  }
}

function loadDevices() {
  showLoading();
  $.ajax({
    url: route('account/security/devices/get'),
    method: 'POST',
    headers: headers(),
    success(res) {
      if (res && !res.error) {
        renderDevices(res.data || []);
      } else {
        const msg = (res && res.message) ? res.message : 'Failed to load devices';
        if ($devicesList.length) {
          $devicesList.html(`<div class="text-center text-danger py-4">${msg}</div>`);
        } else {
          $devicesTbody.html(`<tr><td colspan="6" class="text-center text-danger py-4">${msg}</td></tr>`);
        }
      }
    },
    error() {
      if ($devicesList.length) {
        $devicesList.html(`<div class="text-center text-danger py-4">Network error</div>`);
      } else {
        $devicesTbody.html(`<tr><td colspan="6" class="text-center text-danger py-4">Network error</td></tr>`);
      }
    }
  });
}

/* ---------- reset password (inline form) ---------- */
$('#resetPwdForm').on('submit', function (e) {
  e.preventDefault();
  resetPwdSubmit.hide();
  resetPwdSpinner.show();

  const formdata = new FormData(this);
  $.ajax({
    url: route('account/reset-password'),
    method: 'POST',
    headers: headers(),
    data: formdata,
    cache: false,
    processData: false,
    contentType: false,
    success(response) {
      resetPwdSpinner.hide();
      resetPwdSubmit.show();
      if (!response.error) {
        toast.success(response.message || 'Password updated');
        setTimeout(() => {
          if (response.data?.redirect) location.href = response.data.redirect;
          else location.reload();
        }, 1200);
      } else {
        toast.error(response.message || 'Update failed');
      }
    },
    error(xhr) {
      resetPwdSpinner.hide();
      resetPwdSubmit.show();
      let err = {};
      try { err = JSON.parse(xhr.responseText); } catch (e) { err.message = 'Unexpected error'; }
      if (xhr.status === 422 && err.errors) {
        Object.values(err.errors).flat().forEach(m => toast.error(m));
      } else {
        toast.error(err.message || 'Server error');
      }
    }
  });
});

/* ---------- revoke single device ---------- */
function removeDeviceRowById(id) {
  if ($devicesList.length) {
    $devicesList.find(`.device-item[data-id="${id}"]`).fadeOut(200, function(){ $(this).remove(); });
  } else {
    $devicesTbody.find(`tr[data-id="${id}"]`).fadeOut(200, function(){ $(this).remove(); });
  }
}

// delegate for both layouts
$devicesList.on('click', '.revoke-device', handleRevokeClick);
$devicesTbody.on('click', '.revoke-device', handleRevokeClick);

function handleRevokeClick() {
  const $btn = $(this);
  const $row = $btn.closest($devicesList.length ? '.device-item' : 'tr');
  const id   = $row.data('id');

  $btn.prop('disabled', true).append(' <i class="fas fa-spinner fa-spin"></i>');

  $.ajax({
    url: route('account/security/devices/revoke'),
    method: 'POST',
    headers: headers(),
    data: { id },
    success(res) {
      if (!res.error) {
        removeDeviceRowById(id);
        toast.success(res.message || 'Device revoked');
      } else {
        $btn.prop('disabled', false).find('.fa-spinner').remove();
        toast.error(res.message || 'Failed to revoke');
      }
    },
    error() {
      $btn.prop('disabled', false).find('.fa-spinner').remove();
      toast.error('Network error');
    }
  });
}

/* ---------- revoke other devices ---------- */
$revokeOthers.on('click', function () {
  if (!confirm('Sign out from all other devices?')) return;

  const $btn = $(this);
  const currentUid = getCookie('device_uid') || '';

  $btn.prop('disabled', true).append(' <i class="fas fa-spinner fa-spin"></i>');
  $.ajax({
    url: route('account/security/devices/revoke-others'),
    method: 'POST',
    headers: headers(),
    data: { device_uid: currentUid },
    success(res) {
      if (!res.error) {
        if ($devicesList.length) {
          $devicesList.find('.device-item').each(function () {
            if ($(this).data('uid') !== currentUid) $(this).remove();
          });
        } else {
          $devicesTbody.find('tr').each(function () {
            if ($(this).data('uid') !== currentUid) $(this).remove();
          });
        }
        toast.success(res.message || 'Signed out from other devices');
      } else {
        toast.error(res.message || 'Failed');
        $btn.prop('disabled', false).find('.fa-spinner').remove();
      }
    },
    error() {
      toast.error('Network error');
      $btn.prop('disabled', false).find('.fa-spinner').remove();
    }
  });
});

/* ---------- boot ---------- */
$(document).ready(function () {
  loadDevices();
});
