@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Real Estate FAQs')
@section('description', 'Find answers to commonly asked questions about buying, selling, renting, and investing in real estate in Kenya.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Find answers to commonly asked questions about buying, selling, renting, and investing in real estate.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative">
                <input type="text" 
                       id="faqSearch" 
                       placeholder="Search for answers..." 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <button id="clearSearch" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>

        <!-- Category Filters -->
        <div class="flex flex-wrap gap-2 mb-8">
            <button class="category-filter px-4 py-2 rounded-full text-sm font-medium transition bg-emerald-600 text-white" data-category="all">
                All Questions
            </button>
            @foreach($categories ?? [] as $category)
            <button class="category-filter px-4 py-2 rounded-full text-sm font-medium transition bg-gray-100 text-gray-700 hover:bg-gray-200" data-category="{{ $category }}">
                {{ $category }}
            </button>
            @endforeach
        </div>

        <!-- FAQ Results -->
        <div id="faqResults" class="space-y-4">
            @forelse($groupedFaqs ?? [] as $category => $faqs)
                <div class="faq-category" data-category="{{ $category }}">
                    @if($category)
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $category }}</h2>
                    @endif
                    
                    @foreach($faqs as $faq)
                    <div class="faq-item bg-white rounded-xl shadow-md overflow-hidden mb-4" 
                         data-question="{{ strtolower($faq->question) }}" 
                         data-answer="{{ strtolower($faq->answer) }}"
                         data-has-video="{{ $faq->hasVideo() ? 'true' : 'false' }}">
                        
                        <button class="faq-question w-full text-left px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                @if($faq->hasVideo())
                                    <i class="fas fa-play-circle text-emerald-500"></i>
                                @endif
                                <span class="font-semibold text-gray-900">{{ $faq->question }}</span>
                            </div>
                            <span class="text-emerald-600 transform transition-transform duration-300">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </button>
                        
                        <div class="faq-answer px-6 pb-4 hidden">
                            <div class="pt-2 border-t border-gray-100">
                                <!-- Video Section -->
                                @if($faq->hasVideo())
                                    <div class="mb-4">
                                        @if($faq->video_type === 'local')
                                            <video controls class="w-full rounded-lg shadow-sm max-h-96" poster="{{ asset('images/video-placeholder.jpg') }}">
                                                <source src="{{ asset('storage/' . $faq->video_url) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <div class="relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                                <iframe 
                                                    src="{{ $faq->getVideoEmbedUrl() }}"
                                                    class="absolute top-0 left-0 w-full h-full rounded-lg shadow-sm"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen>
                                                </iframe>
                                            </div>
                                        @endif
                                        
                                        @if($faq->video_title)
                                            <p class="mt-2 text-sm font-medium text-gray-700">{{ $faq->video_title }}</p>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Answer Text -->
                                <div class="text-gray-600 leading-relaxed">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-question-circle text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No FAQs Available</h3>
                <p class="text-gray-500">Check back soon for answers to common questions.</p>
            </div>
            @endforelse
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center py-12 hidden">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">No Results Found</h3>
            <p class="text-gray-500">Try adjusting your search terms or browse by category.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Accordion
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(button => {
            button.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('.fa-chevron-down');
                
                // Toggle current answer
                answer.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
                
                // Auto-play video when expanded
                if (!answer.classList.contains('hidden')) {
                    const video = answer.querySelector('video');
                    if (video) {
                        video.play().catch(e => console.log('Auto-play prevented'));
                    }
                }
            });
        });

        // Category Filter
        const categoryButtons = document.querySelectorAll('.category-filter');
        const faqItems = document.querySelectorAll('.faq-item');
        const faqCategories = document.querySelectorAll('.faq-category');
        const noResults = document.getElementById('noResults');
        const searchInput = document.getElementById('faqSearch');
        const clearSearch = document.getElementById('clearSearch');

        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update button styles
                categoryButtons.forEach(btn => {
                    btn.classList.remove('bg-emerald-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                });
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('bg-emerald-600', 'text-white');

                const category = this.dataset.category;
                
                // Reset search
                searchInput.value = '';
                clearSearch.classList.add('hidden');

                if (category === 'all') {
                    faqCategories.forEach(cat => cat.style.display = 'block');
                    faqItems.forEach(item => item.style.display = 'block');
                    noResults.classList.add('hidden');
                } else {
                    let visibleCount = 0;
                    faqCategories.forEach(cat => {
                        if (cat.dataset.category === category) {
                            cat.style.display = 'block';
                            visibleCount += cat.querySelectorAll('.faq-item').length;
                        } else {
                            cat.style.display = 'none';
                        }
                    });
                    
                    noResults.classList.toggle('hidden', visibleCount > 0);
                }
            });
        });

        // Search Functionality
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query.length > 0) {
                clearSearch.classList.remove('hidden');
            } else {
                clearSearch.classList.add('hidden');
            }

            // Show all categories first
            faqCategories.forEach(cat => cat.style.display = 'block');

            let visibleCount = 0;
            faqItems.forEach(item => {
                const question = item.dataset.question || '';
                const answer = item.dataset.answer || '';
                const matches = question.includes(query) || answer.includes(query);
                
                if (query.length === 0) {
                    item.style.display = 'block';
                    visibleCount++;
                } else if (matches) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Hide categories with no visible items
            faqCategories.forEach(cat => {
                const visibleItems = cat.querySelectorAll('.faq-item[style*="display: block"]');
                if (visibleItems.length === 0) {
                    cat.style.display = 'none';
                } else {
                    cat.style.display = 'block';
                }
            });

            noResults.classList.toggle('hidden', visibleCount > 0);
        });

        // Clear Search
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            this.classList.add('hidden');
            
            // Reset to show all
            faqCategories.forEach(cat => cat.style.display = 'block');
            faqItems.forEach(item => item.style.display = 'block');
            noResults.classList.add('hidden');
            
            // Reset category filter to "All"
            categoryButtons.forEach(btn => {
                btn.classList.remove('bg-emerald-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            document.querySelector('.category-filter[data-category="all"]')?.classList.add('bg-emerald-600', 'text-white');
        });

        // Keyboard shortcut: Press "/" to focus search
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .faq-question .fa-chevron-down {
        transition: transform 0.3s ease;
    }
    .faq-question .fa-chevron-down.rotate-180 {
        transform: rotate(180deg);
    }
    .faq-answer {
        transition: all 0.3s ease;
    }
    video {
        max-width: 100%;
        background: #000;
    }
</style>
@endpush
@endsection