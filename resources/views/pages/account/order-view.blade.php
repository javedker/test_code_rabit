@extends('layout.master')

@section('title', 'Order Details')

@section('content')
  @if($order['error'])
    <div id="orders-error" class="container col-md-6 offset-md-3 mt-5">
      <div class="p-4 text-center" role="alert">
        <div class="mb-3">
          <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
        </div>
        <h4 id="orders-error-message" class="alert-heading fw-bold">
          {{ $order['message'] }}
        </h4>
        <hr>
        <div class="tptrack__btn mt-4">
          <a href="{{ route('articles') }}">
            <button class="tptrack__submition">
              <i class="fal fa-long-arrow-left mx-2"></i>Continue Shopping
            </button>
          </a>
        </div>
      </div>
    </div>
  @else
    @php
      $o = $order['data'][0];
      $address = $o['address'] ?? null;
      $timeline = collect(json_decode($o['timeline'], true))->pluck('status')->toArray();
      $o['timeline'] = json_decode($o['timeline'], true);

      $steps = [
        'order_placed' => ['label' => 'Order Placed', 'icon' => 'clipboard-check', 'color' => '#3182CE'],
        'confirmed' => ['label' => 'Order Confirmed', 'icon' => 'check-circle', 'color' => '#D69E2E'],
        'out_for_delivery' => ['label' => 'Out for Delivery', 'icon' => 'truck', 'color' => '#4FD1C5'],
        'delivered' => ['label' => 'Order Delivered', 'icon' => 'home', 'color' => '#38A169'],
      ];

      $doneCount = count(array_intersect(array_keys($steps), $timeline));
      $pct = floor(100 * $doneCount / max(1, count($steps)));

      $known = isset($steps[$o['status']]);
    @endphp

    <div class="container od-wrap my-4 my-md-5">

      {{-- HERO HEADER --}}
      <div class="od-hero p-2 mb-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
          <div>
            <h2 class="mb-1">Order #{{ $o['ecommerce_order_id'] }}</h2>
          </div>
          <div class="d-flex align-items-center gap-2">
            @if($known)
              <span class="badge rounded-pill px-3 py-2 bg-primary">
                {{ $steps[$o['status']]['label'] }}
              </span>

            @else
              <span class="badge rounded-pill px-3 py-2 bg-danger">
                Order {{ ucfirst($o['status'] ?? 'unknown') }}
              </span>
            @endif
          </div>
        </div>

        <div class="card mt-3">
          <div class="card-body text-end">
            {{-- Timeline or Fallback Alert --}}
            @if($known)
              <div class="od-timeline-horizontal">
                <div class="progress-line"></div>
                @foreach($steps as $key => $step)
                  @php $active = in_array($key, $timeline); @endphp
                  <div class="od-timeline-h-step {{ $active ? 'active' : '' }}" style="--step-color: {{ $step['color'] }};">
                    <div class="dot">
                      <i class="fas fa-{{ $step['icon'] }}"></i>
                    </div>
                    <div class="label text-primary">
                      {{ $step['label'] }}
                      <br>
                      <small>
                        @foreach ($o['timeline'] as ['status' => $status, 'date' => $date])
                          @if ($status === $key)
                            {{ \Carbon\Carbon::parse($date)->format('F j, Y, g:i A') }}
                          @endif
                        @endforeach
                        @if ($key == 'confirmed')
                          @php
                            // Fallback if not found
                            $baseDate = null;
                            $baseDate = $baseDate ?: now();

                            // 2) Compute ETA excluding Fri & Sat using your helper (already available)
                            $etaFrom = addWorkingDaysExcludeFriSat($baseDate, 5);
                            $etaTo = addWorkingDaysExcludeFriSat($baseDate, 7);
                          @endphp

                          {{-- 3) Render estimated delivery (dates) --}}
                          <div style="font-size:14px;color:#4F4F4F;">
                            Estimated Delivery Date: <br>
                            <strong class="text-capitalize">
                              {{ $etaFrom->format('j') }} {{ strtolower($etaFrom->format('M')) }} - {{ $etaTo->format('j') }}
                              {{ strtolower($etaTo->format('M')) }}
                            </strong>
                            <small style="color:#888;"></small>
                          </div>

                        @endif
                      </small>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="alert alert-danger mt-3 mb-0">
                Order has been {{ ucfirst($o['status'] ?? 'unknown') }}
                @if(!empty($o['timeline'][0]['date']))
                  <br>
                  <small>{{ \Carbon\Carbon::parse($o['timeline'][0]['date'])->format('F j, Y, g:i A') }}</small>
                @endif
              </div>
            @endif

          </div>
        </div>
      </div>

      {{-- SUMMARY / DELIVERY --}}
      <div class="row g-4 mt-1 mb-1">
        <div class="col-md-6">
          <div class="card od-card h-100">
            <div class="card-header bg-white fw-semibold">Order Summary</div>
            <div class="card-body od-block">
              <dl class="row mb-2">
                <dt class="col-6">Sub-total</dt>
                <dd class="col-6 text-end">{!! formatePrice($o['sub_total'], 'oman', true) !!}</dd>
              </dl>
              <dl class="row mb-2">
                <dt class="col-6">VAT</dt>
                <dd class="col-6 text-end">{!! formatePrice($o['tax'], 'oman', true) !!}</dd>
              </dl>
              @if($o['discount'])
                <dl class="row mb-2">
                  <dt class="col-6">Discount</dt>
                  <dd class="col-6 text-end">-{!! formatePrice($o['discount'], 'oman', true) !!}</dd>
                </dl>
              @endif
              <div class="od-divider my-3"></div>
              <dl class="row mb-0">
                <dt class="col-6 h6 mb-0">Total</dt>
                <dd class="col-6 h6 mb-0 text-end">{!! formatePrice($o['final_total'], 'oman', true) !!}</dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card od-card h-100">
            <div class="card-header bg-white fw-semibold">Delivery Details</div>
            <div class="card-body od-block">
              @if($address)
                      <dl class="row mb-2">
                        <dt class="col-5">Recipient</dt>
                        <dd class="col-7 text-end">
                          {{ $address['name'] }}
                          @if(!empty($address['alternative_number']))
                            ({{ $address['alternative_number'] }})
                          @endif
                        </dd>
                      </dl>
                      <dl class="row mb-0">
                        <dt class="col-5">Address</dt>
                        <dd class="col-7 text-end">
                          {{ implode(', ', array_filter([
                  $address['address'] ?? null,
                  $address['street'] ?? null,
                  $address['city'] ?? null,
                  $address['postal_code'] ?? null,
                ])) }}
                        </dd>
                      </dl>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- ITEMS --}}
      <h5 class="mb-3">Items</h5>
      <div class="row g-3 g-md-4 mb-4">
        @foreach($o['items'] as $item)
          <div class="col-6 col-md-4">
            <div class="card od-item h-100 text-center">
              <img src="{{ route('media', ['img' => setMedia($item['thumbnail'])]) }}" class="card-img-top p-3"
                alt="{{ $item['name'] }}">
              <div class="card-body pt-2">
                <h6 class="card-title mb-1">{{ $item['name'] }}</h6>
                <div class="od-muted mb-1">Qty: {{ $item['qty'] }}</div>
                <div class="fw-semibold">{!! formatePrice($item['sub_total'], 'oman', true) !!}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  @endif
@endsection