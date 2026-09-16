@extends('layouts.app')

@section('title', 'Podcasts - Real Estate Insights & Market Updates')
@section('description', 'Listen to our latest podcasts covering real estate insights, market trends, property investment tips, and expert interviews.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Podcasts</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Listen to expert insights, market trends, and investment tips from Kenya's leading real estate professionals.
            </p>
        </div>

        <!-- Featured Podcast -->
        @if(isset($featuredPodcast) && $featuredPodcast)
        <div class="mb-12 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="md:col-span-1 relative h-48 md:h-auto">
                    <img src="{{ $featuredPodcast->thumbnail }}" 
                         alt="{{ $featuredPodcast->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <span class="bg-amber-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-star mr-1"></i> Featured Episode
                        </span>
                    </div>
                </div>
                <div class="md:col-span-2 p-6 flex flex-col justify-center">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="{{ $featuredPodcast->platform_icon }} {{ $featuredPodcast->platform_color }}"></i>
                        <span class="text-sm text-gray-500">{{ $featuredPodcast->platform ?? 'Podcast' }}</span>
                        @if($featuredPodcast->media_type === 'video')
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Video</span>
                        @else
                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Audio</span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $featuredPodcast->title }}</h2>
                    <p class="text-gray-600 mt-2 line-clamp-2">{{ Str::limit($featuredPodcast->description ?? '', 150) }}</p>
                    <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-gray-500">
                        <span><i class="fas fa-user mr-1"></i> {{ $featuredPodcast->host ?? 'Unknown Host' }}</span>
                        @if($featuredPodcast->duration)
                        <span><i class="far fa-clock mr-1"></i> {{ $featuredPodcast->duration_formatted }}</span>
                        @endif
                        <span><i class="fas fa-eye mr-1"></i> {{ number_format($featuredPodcast->views_count) }} views</span>
                    </div>
                    <a href="{{ route('podcasts.show', $featuredPodcast->slug) }}" 
                       class="mt-4 inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-xl hover:bg-emerald-700 transition font-medium self-start">
                        <i class="fas fa-play"></i> Listen Now
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Podcasts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($podcasts ?? [] as $podcast)
            <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <a href="{{ route('podcasts.show', $podcast->slug) }}" class="block">
                    <div class="relative h-48 bg-gray-200">
                        <img src="{{ $podcast->thumbnail }}" 
                             alt="{{ $podcast->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                             loading="lazy">
                        
                        <!-- Media Type Badge -->
                        <div class="absolute top-4 right-4">
                            @if($podcast->media_type === 'video')
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                <i class="fas fa-video mr-1"></i> Video
                            </span>
                            @else
                            <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                <i class="fas fa-microphone mr-1"></i> Audio
                            </span>
                            @endif
                        </div>
                        
                        <!-- Play Button -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <i class="fas fa-play text-emerald-600 text-xl ml-1"></i>
                            </div>
                        </div>
                        
                        <!-- Duration -->
                        @if($podcast->duration)
                        <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-xs">
                            <i class="far fa-clock mr-1"></i> {{ $podcast->duration_formatted }}
                        </div>
                        @endif
                    </div>
                </a>
                
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="{{ $podcast->platform_icon }} {{ $podcast->platform_color }} text-sm"></i>
                        <span class="text-xs text-gray-500">{{ $podcast->platform ?? 'Podcast' }}</span>
                        @if($podcast->is_featured)
                        <span class="text-xs text-amber-500 font-semibold">
                            <i class="fas fa-star mr-1"></i> Featured
                        </span>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">
                        <a href="{{ route('podcasts.show', $podcast->slug) }}" class="hover:text-emerald-600 transition">
                            {{ $podcast->title }}
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                        {{ Str::limit($podcast->description ?? '', 100) }}
                    </p>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-user"></i>
                            <span>{{ $podcast->host ?? 'Unknown' }}</span>
                        </div>
                        <a href="{{ route('podcasts.show', $podcast->slug) }}" 
                           class="text-emerald-600 hover:text-emerald-700 text-sm font-medium flex items-center gap-1">
                            Listen <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16">
                <i class="fas fa-microphone text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Podcasts Available</h3>
                <p class="text-gray-500">Check back soon for new episodes.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($podcasts) && method_exists($podcasts, 'links'))
        <div class="mt-12">
            {{ $podcasts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
