@extends('layout.master')

@section('title', 'Faqs')

@section('content')
<section class="track-area pt-80 pb-40">
    <div class="container my-5">

        {{-- Heading + Search --}}
        <div class="text-center mb-4 faqs-heading">
            <h2 class="mb-4">Frequently Asked Questions</h2>
            <div class="faq-search mx-auto mb-4" style="max-width:1000px;">
                <input id="faq-search" type="text" class="form-control form-control-lg" placeholder="Search FAQs..." />
            </div>
        </div>

        {{-- Accordion --}}
        @if(!empty($faqs))
            <div class="accordion faqs-accordion" id="faqsAccordion">
                @foreach($faqs['data'] as $idx => $faq)
                    <div class="accordion-item faqs-accordion-item" data-question="{{ strtolower($faq['question']) }}">
                        <h2 class="accordion-header" id="heading{{ $idx }}">
                            <button class="accordion-button collapsed faqs-accordion-button" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $idx }}"
                                aria-expanded="false" aria-controls="collapse{{ $idx }}">
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $idx }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $idx }}" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body faqs-accordion-body">
                                {!! $faq['answer'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">
                {{ (count($faqs['data'])) === 0 ? 'Loading FAQs…' : 'No FAQs match your search.' }}
            </p>
        @endif

        {{-- Clear Filter Button (Hidden initially) --}}
        <div class="text-center mt-4">
          
            <a class="tpproduct-details__cart d-none" id="clear-filter">
                <button>Show All</button>
            </a>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('faq-search');
    const accordionItems = document.querySelectorAll('#faqsAccordion .faqs-accordion-item');
    const clearBtn = document.getElementById('clear-filter');

    // --- Client-side live search ---
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visibleCount = 0;
        accordionItems.forEach(item => {
            const txt = item.querySelector('.faqs-accordion-button').textContent.toLowerCase();
            const match = txt.includes(q);
            item.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
        clearBtn.classList.toggle('d-none', q === '');
    });

    // --- Handle URL param `q` ---
    const params = new URLSearchParams(window.location.search);
    const query = params.get('q');
    if (query) {
        const qLower = query.toLowerCase().trim();
        let matchedItem = null;

        accordionItems.forEach(item => {
            const questionText = item.getAttribute('data-question');
            if (questionText.includes(qLower)) {
                item.style.display = '';
                matchedItem = item;
            } else {
                item.style.display = 'none';
            }
        });

        if (matchedItem) {
            const btn = matchedItem.querySelector('.accordion-button');
            const collapse = matchedItem.querySelector('.accordion-collapse');
            btn.classList.remove('collapsed');
            collapse.classList.add('show');
        }

        // show clear button
        clearBtn.classList.remove('d-none');
    }

    // --- Clear Filter Button ---
    clearBtn.addEventListener('click', () => {
        window.location.href = window.location.pathname; // reload without ?q param
    });
});
</script>
@endpush
