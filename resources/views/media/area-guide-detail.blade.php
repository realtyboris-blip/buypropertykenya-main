@extends('layouts.app')

@section('title', $areaGuide->name . ' Area Guide - Neighborhood Information')
@section('description', $areaGuide->description ?? "Detailed area guide for {$areaGuide->name} including schools, hospitals, sports facilities, and more.")

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('area-guides.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to Area Guides
            </a>
        </div>

        <!-- Area Guide Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="relative h-64 md:h-80">
                @if($areaGuide->featured_image)
                <img src="{{ asset('storage/' . $areaGuide->featured_image) }}" 
                     alt="{{ $areaGuide->name }}" 
                     class="w-full h-full object-cover">
                @else
                <div class="w-full h-full bg-gradient-to-r from-emerald-600 to-green-600 flex items-center justify-center">
                    <h1 class="text-5xl md:text-7xl font-bold text-white">{{ $areaGuide->name }}</h1>
                </div>
                @endif
                
                <!-- Video Badge on Header -->
                @if($areaGuide->hasVideo())
                <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-play-circle text-emerald-400"></i>
                    Video Tour Available
                </div>
                @endif
            </div>
            <div class="p-6 md:p-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $areaGuide->name }}</h1>
                <p class="text-gray-600 text-lg leading-relaxed">{{ $areaGuide->description }}</p>
            </div>
        </div>

        <!-- Video Section -->
        @if($areaGuide->hasVideo())
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <i class="fas fa-play-circle text-emerald-500 text-3xl"></i>
                    Video Tour of {{ $areaGuide->name }}
                    @if($areaGuide->video_title)
                        <span class="text-sm font-normal text-gray-500">- {{ $areaGuide->video_title }}</span>
                    @endif
                </h2>
                
                <div class="relative rounded-xl overflow-hidden" style="padding-bottom: 56.25%; height: 0; background: #000;">
                    @if($areaGuide->video_type === 'local')
                        <video 
                            controls 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                            poster="{{ $areaGuide->getVideoThumbnailUrl() ?? asset('images/video-placeholder.jpg') }}"
                            class="w-full">
                            <source src="{{ asset('storage/' . $areaGuide->video_url) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <iframe 
                            src="{{ $areaGuide->getVideoEmbedUrl() }}"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Gallery Section -->
        @if($areaGuide->gallery_images && count($areaGuide->gallery_images) > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                <i class="fas fa-images text-emerald-500"></i>
                Photo Gallery
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($areaGuide->gallery_images as $image)
                <div class="relative aspect-square rounded-lg overflow-hidden group cursor-pointer" onclick="openGalleryModal('{{ asset('storage/' . $image) }}')">
                    <img src="{{ asset('storage/' . $image) }}" 
                         alt="Gallery image" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition duration-300"></i>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Gallery Modal -->
        <div id="galleryModal" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center" onclick="closeGalleryModal()">
            <div class="max-w-4xl w-full mx-4 relative" onclick="event.stopPropagation()">
                <button onclick="closeGalleryModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-3xl">
                    <i class="fas fa-times"></i>
                </button>
                <img id="galleryModalImage" src="" alt="Gallery image" class="w-full rounded-lg shadow-2xl max-h-[80vh] object-contain">
            </div>
        </div>
        @endif

        <!-- Amenities Section -->
        @if($areaGuide->amenities)
        @php
            $amenities = is_array($areaGuide->amenities) ? $areaGuide->amenities : json_decode($areaGuide->amenities, true);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Schools -->
            @if(isset($amenities['schools']) && count($amenities['schools']) > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-school text-2xl text-blue-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Schools</h2>
                </div>
                <ul class="space-y-2">
                    @foreach($amenities['schools'] as $school)
                    <li class="flex items-center gap-2 text-gray-700">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        {{ is_array($school) ? ($school['name'] ?? $school['school'] ?? reset($school)) : $school }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Hospitals -->
            @if(isset($amenities['hospitals']) && count($amenities['hospitals']) > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hospital text-2xl text-red-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Hospitals</h2>
                </div>
                <ul class="space-y-2">
                    @foreach($amenities['hospitals'] as $hospital)
                    <li class="flex items-center gap-2 text-gray-700">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        {{ is_array($hospital) ? ($hospital['name'] ?? $hospital['hospital'] ?? reset($hospital)) : $hospital }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Sports Facilities -->
            @if(isset($amenities['sports']) && count($amenities['sports']) > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-running text-2xl text-green-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Sports Facilities</h2>
                </div>
                <ul class="space-y-2">
                    @foreach($amenities['sports'] as $sport)
                    <li class="flex items-center gap-2 text-gray-700">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        {{ is_array($sport) ? ($sport['name'] ?? $sport['facility'] ?? reset($sport)) : $sport }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Shopping -->
            @if(isset($amenities['shopping']) && count($amenities['shopping']) > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-2xl text-yellow-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Shopping</h2>
                </div>
                <ul class="space-y-2">
                    @foreach($amenities['shopping'] as $shop)
                    <li class="flex items-center gap-2 text-gray-700">
                        <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                        {{ is_array($shop) ? ($shop['name'] ?? $shop['center'] ?? reset($shop)) : $shop }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

        <!-- Nearby Places (FIXED) -->
        @if($areaGuide->nearby_places)
        @php
            $places = is_array($areaGuide->nearby_places) ? $areaGuide->nearby_places : json_decode($areaGuide->nearby_places, true);
        @endphp
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-pin text-2xl text-purple-600"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Nearby Places</h2>
            </div>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($places as $place)
                <li class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-location-dot text-emerald-500"></i>
                    {{ is_array($place) ? ($place['place'] ?? $place['name'] ?? reset($place)) : $place }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Properties in this area -->
        @if(isset($properties) && $properties->count() > 0)
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Properties in {{ $areaGuide->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($properties as $property)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="relative h-48">
                        <img src="{{ $property->getFirstImageAttribute() }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">{{ $property->title }}</h3>
                        <p class="text-emerald-600 font-bold">{{ $property->formatted_price }}</p>
                        <a href="{{ route('properties.show', $property->slug) }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium mt-2 inline-block">
                            View Details →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Gallery Modal Functions
    function openGalleryModal(imageSrc) {
        const modal = document.getElementById('galleryModal');
        const modalImage = document.getElementById('galleryModalImage');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeGalleryModal() {
        const modal = document.getElementById('galleryModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGalleryModal();
        }
    });

    // Auto-play video when visible
    document.addEventListener('DOMContentLoaded', function() {
        const videoObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const video = entry.target.querySelector('video');
                    if (video) {
                        // Only autoplay if user hasn't interacted with the page
                        if (!document.hidden) {
                            video.play().catch(e => console.log('Auto-play prevented'));
                        }
                    }
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('video').forEach(video => {
            const container = video.closest('.relative');
            if (container) {
                videoObserver.observe(container);
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    video {
        background: #000;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    /* Gallery Modal Animation */
    #galleryModal {
        animation: fadeIn 0.3s ease;
    }
    #galleryModalImage {
        animation: scaleIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
@endpush
@endsection