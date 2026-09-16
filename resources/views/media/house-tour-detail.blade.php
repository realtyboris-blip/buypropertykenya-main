@extends('layouts.app')

@section('title', $tour->title . ' - House Tour')
@section('description', $tour->description ?? 'Take a virtual tour of this stunning property.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('house-tours.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to House Tours
            </a>
        </div>

        <!-- Video Player -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gray-900">
                <div class="relative" style="padding-bottom: 56.25%;">
                    @if($tour->embed_url)
                    <iframe src="{{ $tour->embed_url }}?autoplay=1&rel=0" 
                            class="absolute top-0 left-0 w-full h-full border-0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                    @elseif($tour->video_url)
                    <video controls class="absolute top-0 left-0 w-full h-full" poster="{{ $tour->thumbnail }}">
                        <source src="{{ $tour->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @else
                    <div class="absolute inset-0 flex items-center justify-center text-white">
                        <div class="text-center">
                            <i class="fas fa-video-slash text-6xl opacity-50 mb-4"></i>
                            <p class="text-lg">Video not available</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    @if($tour->is_featured)
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                    @endif
                    @if($tour->property)
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-home mr-1"></i> Property Tour
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $tour->title }}</h1>

                @if($tour->property)
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-600">
                    <span><i class="fas fa-map-marker-alt text-emerald-500 mr-1"></i> {{ $tour->property->address }}, {{ $tour->property->city }}</span>
                    <span><i class="fas fa-tag text-emerald-500 mr-1"></i> {{ $tour->property->formatted_price }}</span>
                    @if($tour->duration)
                    <span><i class="far fa-clock text-emerald-500 mr-1"></i> {{ $tour->duration_formatted }}</span>
                    @endif
                    <span><i class="fas fa-eye text-emerald-500 mr-1"></i> {{ number_format($tour->views_count) }} views</span>
                </div>
                @endif

                <div class="prose prose-lg max-w-none text-gray-700">
                    <p>{{ $tour->description }}</p>
                </div>

                @if($tour->property)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('properties.show', $tour->property->slug) }}" 
                           class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl hover:bg-emerald-700 transition font-medium">
                            <i class="fas fa-info-circle mr-2"></i> View Property Details
                        </a>
                        <a href="{{ route('lead.form') }}?property={{ $tour->property->slug }}" 
                           class="border-2 border-emerald-600 text-emerald-600 px-6 py-2.5 rounded-xl hover:bg-emerald-50 transition font-medium">
                            <i class="fas fa-envelope mr-2"></i> Request Information
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Tours -->
        @if(isset($relatedTours) && $relatedTours->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">You Might Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedTours as $related)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('house-tours.show', $related->id) }}" class="block">
                        <div class="relative h-48 bg-gray-200">
                            <img src="{{ $related->thumbnail }}" 
                                 alt="{{ $related->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center">
                                    <i class="fas fa-play text-lg ml-1 text-emerald-600"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">
                            <a href="{{ route('house-tours.show', $related->id) }}" class="hover:text-emerald-600 transition">
                                {{ $related->title }}
                            </a>
                        </h3>
                        @if($related->property)
                        <p class="text-gray-600 text-xs flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-emerald-500"></i>
                            {{ $related->property->city }}
                        </p>
                        @endif
                        <a href="{{ route('house-tours.show', $related->id) }}" 
                           class="inline-block mt-2 text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                            Watch Tour →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Share Section -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 mb-3">Share this tour</p>
            <div class="flex justify-center gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                   target="_blank" class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($tour->title) }}&url={{ urlencode(url()->current()) }}" 
                   target="_blank" class="w-10 h-10 rounded-full bg-sky-500 hover:bg-sky-600 text-white flex items-center justify-center transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($tour->title . ' - ' . url()->current()) }}" 
                   target="_blank" class="w-10 h-10 rounded-full bg-green-600 hover:bg-green-700 text-white flex items-center justify-center transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <button onclick="navigator.clipboard.writeText('{{ url()->current() }}')" 
                        class="w-10 h-10 rounded-full bg-gray-600 hover:bg-gray-700 text-white flex items-center justify-center transition">
                    <i class="fas fa-link"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
