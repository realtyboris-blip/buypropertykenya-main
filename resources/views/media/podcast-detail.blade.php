@extends('layouts.app')

@section('title', $podcast->title . ' - Podcast')
@section('description', $podcast->description ?? 'Listen to this podcast episode for real estate insights.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('podcasts.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to Podcasts
            </a>
        </div>

        <!-- Podcast Player -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Media Player -->
            <div class="bg-gray-900 p-4 md:p-6">
                @if($podcast->media_type === 'video')
                    <!-- Video Player -->
                    @if($podcast->embed_url)
                    <div class="relative" style="padding-bottom: 56.25%;">
                        <iframe src="{{ $podcast->embed_url }}?autoplay=1&rel=0" 
                                class="absolute top-0 left-0 w-full h-full border-0 rounded-lg"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                    @elseif($podcast->video_url)
                    <video controls class="w-full rounded-lg" poster="{{ $podcast->thumbnail }}">
                        <source src="{{ $podcast->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @else
                    <div class="text-center text-white py-12">
                        <i class="fas fa-video-slash text-6xl opacity-50 mb-4"></i>
                        <p class="text-lg">Video not available</p>
                    </div>
                    @endif
                @else
                    <!-- Audio Player -->
                    <div class="flex flex-col items-center py-6">
                        <img src="{{ $podcast->thumbnail }}" 
                             alt="{{ $podcast->title }}" 
                             class="w-40 h-40 rounded-full object-cover mb-6 shadow-xl">
                        
                        @if($podcast->audio_url)
                        <audio controls class="w-full max-w-lg">
                            <source src="{{ $podcast->audio_url }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        @else
                        <p class="text-white/70">Audio not available</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4 flex-wrap">
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="{{ $podcast->platform_icon }} text-emerald-600"></i>
                        {{ $podcast->platform ?? 'Podcast' }}
                    </span>
                    
                    @if($podcast->media_type === 'video')
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-video mr-1"></i> Video Podcast
                    </span>
                    @else
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-microphone mr-1"></i> Audio Podcast
                    </span>
                    @endif
                    
                    @if($podcast->is_featured)
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $podcast->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                    <span><i class="fas fa-user mr-2"></i> {{ $podcast->host ?? 'Unknown Host' }}</span>
                    <span><i class="fas fa-calendar-alt mr-2"></i> {{ $podcast->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                    @if($podcast->duration)
                    <span><i class="fas fa-clock mr-2"></i> {{ $podcast->duration_formatted }}</span>
                    @endif
                    <span><i class="fas fa-eye mr-2"></i> {{ number_format($podcast->views_count) }} views</span>
                </div>

                <div class="prose prose-lg max-w-none text-gray-700">
                    <p>{{ $podcast->description }}</p>
                </div>

                <!-- Tags -->
                @if($podcast->tags && count($podcast->tags) > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        @foreach($podcast->tags as $tag)
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                            #{{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Share this episode:</p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($podcast->title) }}&url={{ urlencode(url()->current()) }}" 
                           target="_blank" class="w-10 h-10 rounded-full bg-sky-500 hover:bg-sky-600 text-white flex items-center justify-center transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($podcast->title . ' - ' . url()->current()) }}" 
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

        <!-- Related Podcasts -->
        @if(isset($relatedPodcasts) && $relatedPodcasts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">You Might Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPodcasts as $related)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('podcasts.show', $related->slug) }}" class="block">
                        <div class="relative h-40 bg-gray-200">
                            <img src="{{ $related->thumbnail }}" 
                                 alt="{{ $related->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center">
                                    <i class="fas fa-play text-lg ml-1 text-emerald-600"></i>
                                </div>
                            </div>
                            <div class="absolute top-2 right-2">
                                @if($related->media_type === 'video')
                                <span class="bg-red-500 text-white px-2 py-0.5 rounded-full text-xs">Video</span>
                                @else
                                <span class="bg-emerald-500 text-white px-2 py-0.5 rounded-full text-xs">Audio</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">
                            <a href="{{ route('podcasts.show', $related->slug) }}" class="hover:text-emerald-600 transition">
                                {{ $related->title }}
                            </a>
                        </h3>
                        <p class="text-gray-500 text-xs flex items-center gap-1">
                            <i class="fas fa-user"></i> {{ $related->host ?? 'Unknown' }}
                        </p>
                        <a href="{{ route('podcasts.show', $related->slug) }}" 
                           class="inline-block mt-2 text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                            Listen →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection@extends('layouts.app')

@section('title', $podcast->title . ' - Podcast')
@section('description', $podcast->description ?? 'Listen to this podcast episode for real estate insights.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('podcasts.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to Podcasts
            </a>
        </div>

        <!-- Podcast Player -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Media Player -->
            <div class="bg-gray-900 p-4 md:p-6">
                @if($podcast->media_type === 'video')
                    <!-- Video Player -->
                    @if($podcast->embed_url)
                    <div class="relative" style="padding-bottom: 56.25%;">
                        <iframe src="{{ $podcast->embed_url }}?autoplay=1&rel=0" 
                                class="absolute top-0 left-0 w-full h-full border-0 rounded-lg"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                    @elseif($podcast->video_url)
                    <video controls class="w-full rounded-lg" poster="{{ $podcast->thumbnail }}">
                        <source src="{{ $podcast->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @else
                    <div class="text-center text-white py-12">
                        <i class="fas fa-video-slash text-6xl opacity-50 mb-4"></i>
                        <p class="text-lg">Video not available</p>
                    </div>
                    @endif
                @else
                    <!-- Audio Player -->
                    <div class="flex flex-col items-center py-6">
                        <img src="{{ $podcast->thumbnail }}" 
                             alt="{{ $podcast->title }}" 
                             class="w-40 h-40 rounded-full object-cover mb-6 shadow-xl">
                        
                        @if($podcast->audio_url)
                        <audio controls class="w-full max-w-lg">
                            <source src="{{ $podcast->audio_url }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        @else
                        <p class="text-white/70">Audio not available</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4 flex-wrap">
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="{{ $podcast->platform_icon }} text-emerald-600"></i>
                        {{ $podcast->platform ?? 'Podcast' }}
                    </span>
                    
                    @if($podcast->media_type === 'video')
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-video mr-1"></i> Video Podcast
                    </span>
                    @else
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-microphone mr-1"></i> Audio Podcast
                    </span>
                    @endif
                    
                    @if($podcast->is_featured)
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $podcast->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                    <span><i class="fas fa-user mr-2"></i> {{ $podcast->host ?? 'Unknown Host' }}</span>
                    <span><i class="fas fa-calendar-alt mr-2"></i> {{ $podcast->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                    @if($podcast->duration)
                    <span><i class="fas fa-clock mr-2"></i> {{ $podcast->duration_formatted }}</span>
                    @endif
                    <span><i class="fas fa-eye mr-2"></i> {{ number_format($podcast->views_count) }} views</span>
                </div>

                <div class="prose prose-lg max-w-none text-gray-700">
                    <p>{{ $podcast->description }}</p>
                </div>

                <!-- Tags -->
                @if($podcast->tags && count($podcast->tags) > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        @foreach($podcast->tags as $tag)
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                            #{{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Share this episode:</p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($podcast->title) }}&url={{ urlencode(url()->current()) }}" 
                           target="_blank" class="w-10 h-10 rounded-full bg-sky-500 hover:bg-sky-600 text-white flex items-center justify-center transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($podcast->title . ' - ' . url()->current()) }}" 
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

        <!-- Related Podcasts -->
        @if(isset($relatedPodcasts) && $relatedPodcasts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">You Might Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPodcasts as $related)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('podcasts.show', $related->slug) }}" class="block">
                        <div class="relative h-40 bg-gray-200">
                            <img src="{{ $related->thumbnail }}" 
                                 alt="{{ $related->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center">
                                    <i class="fas fa-play text-lg ml-1 text-emerald-600"></i>
                                </div>
                            </div>
                            <div class="absolute top-2 right-2">
                                @if($related->media_type === 'video')
                                <span class="bg-red-500 text-white px-2 py-0.5 rounded-full text-xs">Video</span>
                                @else
                                <span class="bg-emerald-500 text-white px-2 py-0.5 rounded-full text-xs">Audio</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">
                            <a href="{{ route('podcasts.show', $related->slug) }}" class="hover:text-emerald-600 transition">
                                {{ $related->title }}
                            </a>
                        </h3>
                        <p class="text-gray-500 text-xs flex items-center gap-1">
                            <i class="fas fa-user"></i> {{ $related->host ?? 'Unknown' }}
                        </p>
                        <a href="{{ route('podcasts.show', $related->slug) }}" 
                           class="inline-block mt-2 text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                            Listen →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
