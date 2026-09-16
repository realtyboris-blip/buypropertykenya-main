@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --bp-emerald-deep:#064e3b;
        --bp-emerald:#0d7a5f;
        --bp-emerald-soft:#e8f1ec;
        --bp-gold:#c9a84c;
        --bp-gold-soft:#f0d78c;
        --bp-cream:#f7f3e9;
        --bp-cream-deep:#f5f0e0;
        --bp-ink:#0f1d18;
        --bp-muted:#5b6862;
    }
    .bp-display{ font-family:'Cormorant Garamond', serif; letter-spacing:-0.01em; }
    .bp-eyebrow{
        font-family:'Inter',sans-serif; font-weight:600; font-size:11px;
        letter-spacing:.28em; text-transform:uppercase; color:var(--bp-gold);
    }
    .bp-card{
        background:#fff; border:1px solid rgba(6,78,59,.07);
        box-shadow:0 1px 2px rgba(6,78,59,.04), 0 24px 48px -28px rgba(6,78,59,.18);
        transition:all .5s cubic-bezier(.2,.7,.2,1);
    }
    .bp-card:hover{
        transform:translateY(-6px);
        box-shadow:0 1px 2px rgba(6,78,59,.06), 0 36px 60px -28px rgba(6,78,59,.28);
        border-color:rgba(201,168,76,.35);
    }
    .bp-chip-gold{ background:linear-gradient(135deg,var(--bp-gold),var(--bp-gold-soft)); color:var(--bp-emerald-deep); }
    .bp-chip-emerald{ background:rgba(6,78,59,.92); color:#fff; backdrop-filter:blur(4px); }
    .bp-grad-text{
        background:linear-gradient(135deg,var(--bp-emerald-deep) 0%, var(--bp-emerald) 60%, var(--bp-gold) 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .bp-grad-bg-rich{
        background:radial-gradient(120% 80% at 20% 0%, rgba(201,168,76,.15) 0%, transparent 50%),
                   linear-gradient(135deg,var(--bp-emerald-deep) 0%, #053d2f 50%, var(--bp-emerald) 100%);
    }
    .bp-line-clamp-1{ display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
    .bp-line-clamp-2{ display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    @keyframes bp-fadeUp{ from{opacity:0; transform:translateY(24px);} to{opacity:1; transform:translateY(0);} }
    .bp-fade-up{ animation: bp-fadeUp .9s cubic-bezier(.2,.7,.2,1) both; }
    .bp-noise{
        background-image:radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
        background-size:3px 3px;
    }
</style>
@endpush

@section('title', 'Real Estate Blog - Insights, Tips & Market News')
@section('description', 'Discover the latest real estate insights, market trends, property buying tips, and investment advice from BuyProperty Kenya experts.')

@section('content')
<div class="bp-home" style="background:var(--bp-cream);">

<!-- Blog Header -->
<section class="relative py-24 overflow-hidden bp-grad-bg-rich">
    <div class="absolute inset-0 bp-noise opacity-60"></div>
    <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full filter blur-3xl" style="background:rgba(201,168,76,.15);"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full filter blur-3xl" style="background:rgba(13,122,95,.35);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto bp-fade-up">
            <span class="bp-eyebrow" style="color:var(--bp-gold-soft);">📝 Our Blog</span>
            <h1 class="bp-display text-5xl md:text-7xl font-medium text-white mt-4 leading-[1.05]">
                Real Estate <em class="not-italic" style="
                    background:linear-gradient(135deg,var(--bp-gold-soft),var(--bp-gold));
                    -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">Insights</em>
            </h1>
            <p class="mt-6 text-white/75 text-lg max-w-2xl mx-auto font-light leading-relaxed">
                Expert advice, market trends, and property investment tips from Kenya's leading real estate experts.
            </p>
        </div>
    </div>
</section>

<!-- Blog Grid -->
<section class="py-24" style="background:var(--bp-cream);">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Category Filters -->
        <div class="flex flex-wrap items-center gap-3 mb-10 pb-4 border-b" style="border-color:rgba(6,78,59,.1);">
            <span class="text-xs font-semibold tracking-[.2em] uppercase text-gray-500 mr-2">Filter:</span>
            <a href="{{ route('blog.index') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ !request('category') ? 'text-white shadow-md hover:shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
               style="{{ !request('category') ? 'background:linear-gradient(135deg,var(--bp-emerald-deep),var(--bp-emerald));' : '' }}">
                All Posts
            </a>
            @foreach($categories ?? [] as $category)
            <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request('category') == $category->slug ? 'text-white shadow-md hover:shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
               style="{{ request('category') == $category->slug ? 'background:linear-gradient(135deg,var(--bp-emerald-deep),var(--bp-emerald));' : '' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
            <article class="bp-card rounded-2xl overflow-hidden flex flex-col">
                <div class="relative overflow-hidden h-56">
                    @if($blog->featured_image)
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}"
                         class="w-full h-full object-cover transition duration-[1200ms] ease-out hover:scale-110"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    @else
                    <div class="w-full h-full flex items-center justify-center" style="background:var(--bp-emerald-soft);">
                        <i class="fas fa-file-alt text-5xl" style="color:var(--bp-emerald); opacity:0.5;"></i>
                    </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>

                    @if($blog->is_featured)
                    <div class="absolute top-4 right-4">
                        <span class="bp-chip-gold inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold shadow-lg">
                            <i class="fas fa-star text-[10px]"></i> Featured
                        </span>
                    </div>
                    @endif

                    @if($blog->category)
                    <div class="absolute bottom-4 left-4">
                        <span class="bp-chip-emerald inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium shadow-lg">
                            <i class="fas fa-folder-open text-[10px]" style="color:var(--bp-gold);"></i>
                            {{ $blog->category->name }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center gap-4 text-xs" style="color:var(--bp-muted);">
                        <span class="flex items-center gap-1">
                            <i class="far fa-calendar-alt" style="color:var(--bp-emerald);"></i>
                            {{ $blog->published_at?->format('M d, Y') ?? 'Draft' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="far fa-clock" style="color:var(--bp-emerald);"></i>
                            {{ $blog->reading_time ?? 1 }} min read
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="far fa-eye" style="color:var(--bp-emerald);"></i>
                            {{ number_format($blog->views_count) }}
                        </span>
                    </div>

                    <h3 class="bp-display text-2xl font-medium mt-3 bp-line-clamp-2" style="color:var(--bp-emerald-deep);">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="hover:underline hover:opacity-80 transition">
                            {{ $blog->title }}
                        </a>
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed bp-line-clamp-2 flex-1" style="color:var(--bp-muted);">
                        {{ $blog->excerpt }}
                    </p>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-dashed" style="border-color:rgba(6,78,59,.1);">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold"
                                 style="background:linear-gradient(135deg,var(--bp-emerald-deep),var(--bp-emerald)); color:var(--bp-gold-soft);">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="text-sm font-medium" style="color:var(--bp-emerald-deep);">{{ $blog->author?->name ?? 'Admin' }}</span>
                        </div>
                        <a href="{{ route('blog.show', $blog->slug) }}" 
                           class="inline-flex items-center gap-1 text-sm font-semibold transition group" style="color:var(--bp-emerald);">
                            Read More 
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-16" style="color:var(--bp-muted);">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4" style="background:var(--bp-emerald-soft);">
                    <i class="fas fa-newspaper text-4xl" style="color:var(--bp-emerald);"></i>
                </div>
                <h3 class="bp-display text-2xl font-medium" style="color:var(--bp-emerald-deep);">No Blog Posts Yet</h3>
                <p class="mt-2 text-sm">Check back soon for new insights and articles.</p>
                <p class="mt-4 text-xs tracking-wide uppercase" style="color:var(--bp-emerald);">Subscribe to our newsletter to stay updated</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($blogs->hasPages())
        <div class="mt-12 flex justify-center">
            <div class="bp-card rounded-xl px-6 py-4">
                {{ $blogs->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

</div>
@endsection