@extends('layouts.app')

@section('title', $property->title . ' - For ' . ucfirst($property->listing_type ?? 'Sale'))

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fas fa-arrow-left"></i> Back to Properties
            </a>
        </div>

        <!-- Property Details -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 lg:p-8">
                <!-- Image Gallery / Video -->
                <div>
                    @if($property->hasVideo())
                        <!-- Video Thumbnail with Play Button -->
                        <div class="relative rounded-xl overflow-hidden bg-gray-900 group cursor-pointer" 
                             x-data="{ showVideo: false }"
                             @click="showVideo = true">
                            <img src="{{ $property->video_thumbnail ?? $property->getFeaturedImageAttribute() }}" 
                                 alt="{{ $property->title }} - Video Tour"
                                 class="w-full h-96 object-cover transition duration-500 group-hover:scale-105">
                            
                            <!-- Play Button Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/50 transition-all duration-300">
                                <div class="w-20 h-20 rounded-full bg-white/90 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                                    <i class="fas fa-play text-2xl ml-1 text-emerald-700"></i>
                                </div>
                            </div>
                            
                            <!-- Video Badge -->
                            <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-2">
                                <i class="fas fa-video"></i>
                                Video Tour
                            </div>
                            
                            <!-- Featured Badge -->
                            @if($property->is_featured)
                            <div class="absolute top-4 right-4">
                                <span class="bg-amber-500 text-white px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            </div>
                            @endif
                            
                            <!-- Video Modal -->
                            <div x-show="showVideo" 
                                 x-transition:enter.duration.300ms
                                 x-cloak
                                 class="fixed inset-0 z-[999] flex items-center justify-center bg-black/95 p-4"
                                 @click.away="showVideo = false"
                                 @keydown.escape.window="showVideo = false">
                                
                                <div class="relative w-full max-w-4xl bg-black rounded-2xl overflow-hidden">
                                    <!-- Close Button -->
                                    <button @click="showVideo = false" 
                                            class="absolute top-3 right-3 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                                        <i class="fas fa-times text-lg"></i>
                                    </button>
                                    
                                    <!-- Video Player -->
                                    <div class="relative" style="padding-bottom: 56.25%;">
                                        @if($property->isYouTubeVideo() || $property->isVimeoVideo())
                                            <iframe 
                                                src="{{ $property->video_url }}?autoplay=1&rel=0" 
                                                class="absolute top-0 left-0 w-full h-full border-0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <video 
                                                class="absolute top-0 left-0 w-full h-full"
                                                controls
                                                autoplay
                                                poster="{{ $property->video_thumbnail ?? $property->getFeaturedImageAttribute() }}">
                                                <source src="{{ $property->video_url }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Regular Image -->
                        <div class="rounded-xl overflow-hidden bg-gray-100 relative group">
                            <img src="{{ $property->getFeaturedImageAttribute() }}" alt="{{ $property->title }}"
                                class="w-full h-96 object-cover transition duration-500 group-hover:scale-105">
                            @if($property->is_featured)
                            <div class="absolute top-4 right-4">
                                <span class="bg-amber-500 text-white px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Thumbnail Gallery -->
                    @if($property->getAllImagesAttribute() && count($property->getAllImagesAttribute()) > 1)
                    <div class="grid grid-cols-4 gap-2 mt-3">
                        @foreach($property->getAllImagesAttribute() as $index => $image)
                            @if($index < 4)
                            <div class="rounded-lg overflow-hidden bg-gray-100 cursor-pointer hover:opacity-80 transition">
                                <img src="{{ $image }}" alt="Property image {{ $index + 1 }}"
                                     class="w-full h-20 object-cover">
                            </div>
                            @endif
                        @endforeach
                        @if(count($property->getAllImagesAttribute()) > 4)
                            <div class="rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center cursor-pointer hover:bg-gray-300 transition">
                                <span class="text-gray-600 font-medium text-sm">+{{ count($property->getAllImagesAttribute()) - 4 }} more</span>
                            </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Property Info -->
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between items-start">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $property->title }}</h1>
                        </div>
                        <p class="text-gray-600 mt-2 flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-emerald-600"></i>
                            {{ $property->address }}, {{ $property->city }}, {{ $property->state ?? 'Kenya' }}
                        </p>
                    </div>

                    <div class="border-t border-b py-4 my-4 bg-gradient-to-r from-emerald-50 to-green-50 -mx-4 px-4">
                        <div class="text-4xl font-bold gradient-text">{{ $property->formatted_price }}</div>
                        <div class="flex gap-6 mt-2">
                            @if($property->bedrooms)
                            <span class="flex items-center gap-2 text-gray-700"><i class="fas fa-bed text-emerald-600"></i> {{ $property->bedrooms }} Bedrooms</span>
                            @endif
                            @if($property->bathrooms)
                            <span class="flex items-center gap-2 text-gray-700"><i class="fas fa-bath text-emerald-600"></i> {{ $property->bathrooms }} Bathrooms</span>
                            @endif
                            @if($property->area_sqft)
                            <span class="flex items-center gap-2 text-gray-700"><i class="fas fa-arrows-alt text-emerald-600"></i> {{ $property->area_sqft }} sq ft</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $property->description }}</p>
                    </div>

                    @if($property->amenities)
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Amenities & Features</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($property->amenities ?? [] as $amenity)
                            <span class="bg-gradient-to-r from-emerald-50 to-green-50 text-gray-700 px-3 py-1.5 rounded-full text-sm flex items-center gap-1">
                                <i class="fas fa-check-circle text-emerald-600 text-xs"></i> {{ $amenity['amenity'] ?? $amenity }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <a href="{{ route('lead.form') }}?property={{ $property->slug }}"
                        class="block text-center bg-gradient-to-r from-emerald-600 to-green-600 text-white px-6 py-4 rounded-xl hover:shadow-xl transition-all duration-300 font-semibold text-lg">
                        <i class="fas fa-envelope mr-2"></i> Request More Information
                    </a>
                </div>
            </div>
        </div>

        <!-- Similar Properties -->
        @if(isset($similarProperties) && $similarProperties->count() > 0)
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Similar Properties</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($similarProperties as $similar)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                    <img src="{{ $similar->getFirstImageAttribute() }}" alt="{{ $similar->title }}"
                        class="w-full h-48 object-cover transition duration-500 group-hover:scale-105">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1 line-clamp-1">{{ $similar->title }}</h3>
                        <p class="text-emerald-600 font-bold text-xl">{{ $similar->formatted_price }}</p>
                        <a href="{{ route('properties.show', $similar->slug) }}" class="text-emerald-600 hover:text-emerald-700 text-sm mt-2 inline-block font-medium">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
    // Auto-play video when modal opens
    document.addEventListener('alpine:init', () => {
        Alpine.data('videoModal', (url, poster) => ({
            show: false,
            url: url,
            poster: poster,
            
            open() {
                this.show = true;
                // Prevent body scroll
                document.body.style.overflow = 'hidden';
            },
            
            close() {
                this.show = false;
                document.body.style.overflow = '';
                
                // Stop video if it's a video element
                const video = document.querySelector('#video-player');
                if (video) {
                    video.pause();
                }
            }
        }));
    });
</script>
@endpush
@endsection