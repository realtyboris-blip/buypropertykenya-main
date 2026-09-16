@props(['property'])

@if($property->hasVideo())
<div class="property-video-container mt-8" x-data="{ showVideo: false }">
    <!-- Video Thumbnail -->
    <div class="relative group cursor-pointer rounded-2xl overflow-hidden shadow-lg" @click="showVideo = !showVideo">
        <img src="{{ $property->video_thumbnail ?? asset('images/video-placeholder.jpg') }}" 
             alt="Video Tour of {{ $property->title }}"
             class="w-full h-64 md:h-80 object-cover">
        
        <!-- Play Button Overlay -->
        <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-all duration-300">
            <div class="w-20 h-20 rounded-full bg-white/90 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                <i class="fas fa-play text-2xl ml-1" style="color:var(--bp-emerald-deep);"></i>
            </div>
        </div>
        
        <!-- Video Label -->
        <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-2">
            <i class="fas fa-video"></i>
            Property Tour
        </div>
        
        <!-- Duration Badge (optional) -->
        <div class="absolute top-4 right-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium">
            <i class="fas fa-clock mr-1"></i> Watch Tour
        </div>
    </div>
    
    <!-- Video Modal -->
    <div x-show="showVideo" 
         x-transition:enter.duration.300ms
         x-cloak
         class="fixed inset-0 z-[999] flex items-center justify-center bg-black/90 p-4"
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
                        src="{{ $property->video_url }}?autoplay=1" 
                        class="absolute top-0 left-0 w-full h-full border-0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <video 
                        class="absolute top-0 left-0 w-full h-full"
                        controls
                        autoplay
                        poster="{{ $property->video_thumbnail ?? asset('images/video-placeholder.jpg') }}">
                        <source src="{{ $property->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
@endif
