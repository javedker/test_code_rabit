<section class="dealproduct-area pt-50 pb-1">
    <div class="container">
        <div id="offers-list"><!-- offers will be appended here --></div>

        <!-- Empty / error state -->
        <div id="offers-empty" class="text-center text-muted py-5 d-none">
            {{-- No offers available right now. --}}
        </div>
    </div>
</section>

@push('scripts')
    <script>
        $(function() {
            // =============== Config / helpers ===============
            const offersGetUrl = route('offers/get')
            const fmtOMR = (n) => {
                const num = Number(n || 0);
                return 'OMR ' + num.toFixed(3);
            };

            function computeDiscounted(price, type, amount) {
                const p = Number(price || 0);
                const a = Number(amount || 0);
                if (type === 'percent') return Math.max(0, p * (1 - a / 100));
                if (type === 'fixed') return Math.max(0, p - a);
                return p;
            }

            function setProgress($root, startISO, endISO) {
                const $bar = $root.find('.progress-bar').first();
                if (!$bar.length || !startISO || !endISO) return;
                const now = Date.now();
                const start = new Date(startISO).getTime();
                const end = new Date(endISO).getTime();
                if (isNaN(start) || isNaN(end) || end <= start) return;
                const pct = Math.max(0, Math.min(100, ((now - start) / (end - start)) * 100));
                $bar.css('width', pct.toFixed(0) + '%').attr('aria-valuenow', pct.toFixed(0));
            }

            // Countdown manager: update all timers once per second
            function tickAllCountdowns() {
                const pad2 = (n) => (n < 10 ? '0' + n : '' + n);

                $('[data-countdown-end]').each(function() {
                    const endISO = $(this).attr('data-countdown-end');
                    const $wrap = $(this);
                    const $boxes = $wrap.find('.tpdealcontact__countdown .count-box h3');
                    const $note = $wrap.find('.count-note');

                    const end = new Date(endISO).getTime();
                    const diff = end - Date.now();
                    if (diff <= 0) {
                        $boxes.eq(0).text(0);
                        $boxes.eq(1).text(0);
                        $boxes.eq(2).text('00');
                        $boxes.eq(3).text('00');
                        if ($note.length) $note.text('Offer has ended');
                        return; // keep iterating others
                    }

                    const totalSeconds = Math.floor(diff / 1000);
                    const days = Math.floor(totalSeconds / 86400);
                    const hours = Math.floor((totalSeconds % 86400) / 3600);
                    const mins = Math.floor((totalSeconds % 3600) / 60);
                    const secs = totalSeconds % 60;

                    $boxes.eq(0).text(days);
                    $boxes.eq(1).text(hours);
                    $boxes.eq(2).text(pad2(mins));
                    $boxes.eq(3).text(pad2(secs));
                });
            }

            // Build one offer card (structure matches your markup)
            function createOfferCard(offer) {
                // fallback img if API has no thumbnail
                const fallbackImg = "{{ asset('img/floded/floded-02.png') }}";

                const $card = $(`
                        <div class="theme-bg position-relative mb-4">
                          <!-- Badge top-left -->
                          <a href="#" class="position-absolute top-0 start-0 m-3">
                            <div class="badges p-3 rounded shadow">
                              <p class="mb-0 text-center">
                                <span class="firstLine d-block fw-bold">Offer <br> Of <br> The Month</span>
                              </p>
                            </div>
                          </a>

                          <div class="row">
                            <div class="col-lg-4 col-md-12">
                              <div class="tpdealproduct">
                                <div class="tpdealproduct__thumb p-relative text-center">
                                  <img src="${fallbackImg}" style="mix-blend-mode: multiply" width="300" alt="dealproduct-thumb">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                              <div class="tpdealcontact pt-30">
                                <div class="tpdealcontact__price mb-5">
                                  <del></del>
                                  <span></span>
                                </div>

                                <div class="tpdealcontact__text">
                                  <h4 class="tpdealcontact__title mb-10">
                                    <a href="#"></a>
                                  </h4>
                                  <p></p>
                                </div>

                                <hr>
                                <div class="row">
                                  <div class="col-1"></div>
                                  <div class="col-6">
                                    <h3 class="text-danger  fw-bold mx-2">Offer Ends In</h3>
                                  </div>
                                </div>

                                <div class="tpdealcontact__count " data-countdown-end="">
                                  <div class="tpdealcontact__countdown d-flex gap-3">
                                    <div class="count-box">
                                      <h3>0</h3>
                                      <small>Days</small>
                                    </div>
                                    <div class="count-box">
                                      <h3>0</h3>
                                      <small>Hour</small>
                                    </div>
                                    <div class="count-box">
                                      <h3>00</h3>
                                      <small>Minute</small>
                                    </div>
                                    <div class="count-box">
                                      <h3>00</h3>
                                      <small>Second</small>
                                    </div>
                                  </div>
                                  <!-- <i class="count-note">Remains until the <br> end of the offer</i> -->
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      `);

                // --- Fill data safely
                // Badge (keep your stacked style by inserting <br> between words)
                if (offer.badge) {
                    $card.find('.badges .firstLine').html(offer.badge.replaceAll(' ', ' <br> '));
                }

                // Image
                if (offer.thumbnail) {
                    $card.find('.tpdealproduct__thumb img')
                        .attr('src', route('media/' + setMedia(offer.thumbnail)))
                        .attr('alt', offer.title || 'Offer');
                }

                // Title + link
                if (offer.title) {
                    $card.find('.tpdealcontact__title a')
                        .text(offer.title)
                        .attr('href', offer.target_url || offer.redirect_url || '#');
                }

                // Description
                if (offer.description) {
                    $card.find('.tpdealcontact__text p').text(offer.description);
                }

                // Prices
                const normal = (offer.price !== undefined && offer.price !== null && offer.price !== '') ? Number(
                    offer.price) : null;
                const final = (normal !== null) ?
                    computeDiscounted(normal, offer.discount_type, offer.discount_amount) :
                    null;

                const $price = $card.find('.tpdealcontact__price');
                const $final = $price.find('span').first();
                const $del = $price.find('del').first();

                if (final !== null && normal !== null) {
                    $final.text(fmtOMR(final));
                    $del.text(fmtOMR(normal)).show();
                } else if (normal !== null) {
                    $final.text(fmtOMR(normal));
                    $del.text(`${fmtOMR(normal)} (incl. vat)`).show();
                } else {
                    // If no base price present, show discount text in final spot
                    const txt = offer.discount_type === 'percent' ?
                        (offer.discount_amount ? (offer.discount_amount + '% OFF') : '') :
                        (offer.discount_amount ? ('-' + fmtOMR(offer.discount_amount)) : '');
                    $final.text(txt);
                    $del.hide();
                }

                // Progress & Countdown
                if (offer.starts_at && offer.ends_at) {
                    setProgress($card, offer.starts_at, offer.ends_at);
                    $card.find('.tpdealcontact__count').attr('data-countdown-end', offer.ends_at);
                } else if (offer.ends_at) {
                    $card.find('.tpdealcontact__count').attr('data-countdown-end', offer.ends_at);
                }

                return $card;
            }

            // =============== Fetch & Render all offers ===============
            const $list = $('#offers-list');
            const $empty = $('#offers-empty');

            $.ajax({
                url: offersGetUrl,
                method: 'GET',
                // data: { limit: 12 }, // optional
                success: function(response) {
                    $list.empty();
                    if (!response || response.error || !Array.isArray(response.data) || response.data
                        .length === 0) {
                        $empty.removeClass('d-none');
                        return;
                    }
                    $empty.addClass('d-none');

                    // Append each card
                    response.data.forEach(function(offer) {
                        $list.append(createOfferCard(offer));
                    });

                    // Start the global countdown ticker (once)
                    if (!window.__offersTicker) {
                        window.__offersTicker = setInterval(tickAllCountdowns, 1000);
                        tickAllCountdowns(); // initial
                    }
                },
                error: function() {
                    $list.empty();
                    $empty.removeClass('d-none');
                }
            });
        });
    </script>
@endpush
