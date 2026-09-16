@extends('layouts.app')

@section('title', 'Area Guides - Property Neighborhoods & Local Amenities')
@section('description', 'Discover detailed area guides for Kenya\'s top neighborhoods. Find information about schools, hospitals, sports facilities, and more.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Area Guides</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Discover detailed information about Kenya's top neighborhoods. Find the perfect location for your dream home.
            </p>
        </div>

        <!-- Area Guides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($areaGuides ?? [] as $guide)
            <a href="{{ route('area-guides.show', $guide->slug) }}" 
               class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-48 bg-emerald-100">
                    @if($guide->featured_image)
                    <img src="{{ asset('storage/' . $guide->featured_image) }}" 
                         alt="{{ $guide->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-100 to-green-100">
                        <i class="fas fa-map-marked-alt text-5xl text-emerald-500"></i>
                    </div>
                    @endif
                    
                    <!-- Video Badge -->
                    @if($guide->hasVideo())
                    <div class="absolute top-4 left-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5">
                        <i class="fas fa-play-circle text-emerald-400"></i>
                        Video Tour
                    </div>
                    @endif
                    
                    <!-- Gallery Badge -->
                    @if($guide->gallery_images && count($guide->gallery_images) > 0)
                    <div class="absolute top-4 right-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5">
                        <i class="fas fa-images text-emerald-400"></i>
                        {{ count($guide->gallery_images) }} Photos
                    </div>
                    @endif
                    
                    <div class="absolute bottom-4 left-4">
                        <span class="bg-emerald-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fas fa-map-pin mr-1"></i> Guide
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition">
                        {{ $guide->name }}
                    </h3>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ Str::limit($guide->description ?? "Discover the vibrant neighborhood of {$guide->name}.", 120) }}
                    </p>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-100">
                        @if($guide->amenities)
                            @php
                                $amenities = is_array($guide->amenities) ? $guide->amenities : json_decode($guide->amenities, true);
                            @endphp
                            @if(isset($amenities['schools']) && count($amenities['schools']) > 0)
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <i class="fas fa-school text-emerald-500"></i>
                                <span>{{ count($amenities['schools']) }} Schools</span>
                            </div>
                            @endif
                            @if(isset($amenities['hospitals']) && count($amenities['hospitals']) > 0)
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <i class="fas fa-hospital text-emerald-500"></i>
                                <span>{{ count($amenities['hospitals']) }} Hospitals</span>
                            </div>
                            @endif
                            @if(isset($amenities['sports']) && count($amenities['sports']) > 0)
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <i class="fas fa-running text-emerald-500"></i>
                                <span>{{ count($amenities['sports']) }} Sports</span>
                            </div>
                            @endif
                            @if(isset($amenities['shopping']) && count($amenities['shopping']) > 0)
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <i class="fas fa-shopping-bag text-emerald-500"></i>
                                <span>{{ count($amenities['shopping']) }} Shops</span>
                            </div>
                            @endif
                        @endif
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <i class="fas fa-building text-emerald-500"></i>
                            <span>{{ $guide->properties_count ?? 0 }} Properties</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Area Guides Yet</h3>
                <p class="text-gray-500">Area guides are being generated. Check back soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection