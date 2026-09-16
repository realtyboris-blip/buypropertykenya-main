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
    .bp-grad-bg-rich{
        background:radial-gradient(120% 80% at 20% 0%, rgba(201,168,76,.15) 0%, transparent 50%),
                   linear-gradient(135deg,var(--bp-emerald-deep) 0%, #053d2f 50%, var(--bp-emerald) 100%);
    }
    .bp-grad-text{
        background:linear-gradient(135deg,var(--bp-emerald-deep) 0%, var(--bp-emerald) 60%, var(--bp-gold) 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .bp-noise{
        background-image:radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
        background-size:3px 3px;
    }
    @keyframes bp-fadeUp{ from{opacity:0; transform:translateY(24px);} to{opacity:1; transform:translateY(0);} }
    .bp-fade-up{ animation: bp-fadeUp .9s cubic-bezier(.2,.7,.2,1) both; }
    @keyframes bp-float{ 0%,100%{transform:translateY(0)} 50%{transform:translateY(-18px)} }
    .bp-float{ animation: bp-float 9s ease-in-out infinite; }
</style>
@endpush

@section('title', $blog->meta_title ?? $blog->title . ' | BuyProperty Kenya')
@section('description', $blog->meta_description ?? $blog->excerpt)

@section('content')
<div class="bp-home" style="background:var(--bp-cream);">

<!-- Blog Header -->
<section class="relative py-20 overflow-hidden bp-grad-bg-rich">
    <div class="absolute inset-0 bp-noise opacity-60"></div>
    <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full filter blur-3xl" style="background:rgba(201,168,76,.15);"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full filter blur-3xl" style="background:rgba(13,122,95,.35);"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 lg:px-8">
        <div class="text-center bp-fade-up">
            @if($blog->category)
            <span class="bp-chip-gold inline-block px-3 py-1 rounded-full text-sm font-medium mb-4">
                {{ $blog->category->name }}
            </span>
            @endif
            <h1 class="bp-display text-4xl md:text-6xl font-medium text-white leading-[1.05]">
                {{ $blog->title }}
            </h1>
            <div class="flex flex-wrap items-center justify-center gap-4 mt-5 text-sm" style="color:var(--bp-gold-soft);">
                <span class="flex items-center gap-2"><i class="far fa-user"></i> {{ $blog->author?->name ?? 'Admin' }}</span>
                <span class="flex items-center gap-2"><i class="far fa-calendar-alt"></i> {{ $blog->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                <span class="flex items-center gap-2"><i class="far fa-clock"></i> {{ $blog->reading_time ?? 1 }} min read</span>
                <span class="flex items-center gap-2"><i class="far fa-eye"></i> {{ number_format($blog->views_count) }} views</span>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="py-16" style="background:#fff;">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <!-- Featured Image -->
        @if($blog->featured_image)
        <div class="mb-8 rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" 
                 class="w-full h-auto object-cover max-h-[500px]">
        </div>
        @endif

        <!-- Content -->
        <div class="prose prose-lg max-w-none" style="color:var(--bp-ink);">
            <style>
                .prose h1, .prose h2, .prose h3, .prose h4 {
                    font-family: 'Cormorant Garamond', serif;
                    color: var(--bp-emerald-deep);
                    font-weight: 500;
                }
                .prose h2 { font-size: 2rem; margin-top: 2.5rem; margin-bottom: 1rem; }
                .prose h3 { font-size: 1.5rem; margin-top: 1.8rem; }
                .prose p { line-height: 1.8; color: #374151; }
                .prose ul, .prose ol { color: #374151; }
                .prose li { margin-bottom: 0.5rem; }
                .prose strong { color: var(--bp-emerald-deep); }
                .prose a { color: var(--bp-emerald); text-decoration: underline; }
                .prose a:hover { color: var(--bp-emerald-deep); }
            </style>
            {!! $blog->content !!}
        </div>

        <!-- Tags -->
        @if($blog->tags && $blog->tags->count() > 0)
        <div class="mt-8 pt-8 border-t" style="border-color:rgba(6,78,59,.1);">
            <div class="flex flex-wrap gap-2">
                @foreach($blog->tags as $tag)
                <span class="px-3 py-1 rounded-full text-sm" style="background:var(--bp-emerald-soft); color:var(--bp-emerald-deep);">
                    #{{ $tag->name }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back to Blog -->
        <div class="mt-8 text-center">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 font-medium transition" style="color:var(--bp-emerald);">
                <i class="fas fa-arrow-left"></i> Back to Blog
            </a>
        </div>
    </div>
</section>

<!-- Related Posts -->
@if(isset($relatedPosts) && $relatedPosts->count() > 0)
<section class="py-16" style="background:var(--bp-cream-deep);">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="bp-display text-3xl font-medium mb-8" style="color:var(--bp-emerald-deep);">
            You Might Also Like
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
            @foreach($relatedPosts as $post)
            <article class="bp-card rounded-xl overflow-hidden">
                <div class="relative h-48 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                         class="w-full h-full object-cover transition duration-[1200ms] ease-out hover:scale-110"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    @else
                    <div class="w-full h-full flex items-center justify-center" style="background:var(--bp-emerald-soft);">
                        <i class="fas fa-file-alt text-3xl" style="color:var(--bp-emerald); opacity:0.5;"></i>
                    </div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="bp-display text-xl font-medium bp-line-clamp-1" style="color:var(--bp-emerald-deep);">
                        {{ $post->title }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed bp-line-clamp-2" style="color:var(--bp-muted);">
                        {{ $post->excerpt }}
                    </p>
                    <a href="{{ route('blog.show', $post->slug) }}" 
                       class="inline-flex items-center gap-1 mt-3 text-sm font-semibold transition group" style="color:var(--bp-emerald);">
                        Read More <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

</div>
@endsection