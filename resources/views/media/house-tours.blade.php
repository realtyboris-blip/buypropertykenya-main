@extends('layouts.app')

@section('title', 'House Tours - Property Video Tours')
@section('description', 'Explore our collection of property video tours. Take a virtual walkthrough of your dream home.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">House Tours</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Take a virtual walkthrough of our featured properties. Explore every corner from the comfort of your home.
            </p>
        </div>

        <!-- Featured Tour (if any) -->
        @php
            $featuredTour = $houseTours->where('is_featured', true)->first();
        @endphp

        @if($featuredTour)
        <div class="mb-12">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="relative bg-gray-900 min-h-[300px] lg:min-h-[400px]">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-play text-3xl text-white"></i>
                                </div>
                                <p class="text-sm opacity-75">Featured Tour</p>
                            </div>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-emerald-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-star mr-1"></i> Featured
                            </span>
                        </div>
                        @if($featuredTour->duration)
                        <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-xs">
                            <i class="far fa-clock mr-1"></i> {{ $featuredTour->duration_formatted }}
                        </div>
                        @endif
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col justify-center">
                        <span class="text-emerald-600 text-sm font-semibold uppercase tracking-wider">Featured Tour</span>
                        <h2 class="text-2xl font-bold text-gray-900 mt-2">{{ $featuredTour->title }}</h2>
                        <p class="text-gray-600 mt-2">{{ Str::limit($featuredTour->description ?? 'Take a virtual tour of this stunning property.', 150) }}</p>
                        <div class="flex items-center gap-4 mt-4 text-sm text-gray-500">
                            @if($featuredTour->property)
                            <span><i class="fas fa-map-marker-alt text-emerald-500 mr-1"></i> {{ $featuredTour->property->city }}</span>
                            <span><i class="fas fa-home text-emerald-500 mr-1"></i> {{ $featuredTour->property->title }}</span>
                            @endif
                        </div>
                        <a href="{{ route('house-tours.show', $featuredTour->id) }}" 
                           class="mt-6 inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl hover:bg-emerald-700 transition">
                            <i class="fas fa-play"></i> Watch Tour
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tour Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($houseTours as $tour)
            <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <a href="{{ route('house-tours.show', $tour->id) }}" class="block">
                    <div class="relative h-56 bg-gray-200">
                        <img src="{{ $tour->thumbnail }}" 
                             alt="{{ $tour->title }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                             loading="lazy">
                        
                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center transform group-hover:scale-110 transition">
                                <i class="fas fa-play text-2xl ml-1 text-emerald-600"></i>
                            </div>
                        </div>

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            @if($tour->is_featured)
                            <span class="bg-amber-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-star mr-1"></i> Featured
                            </span>
                            @endif
                        </div>

                        @if($tour->duration)
                        <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-xs">
                            <i class="far fa-clock mr-1"></i> {{ $tour->duration_formatted }}
                        </div>
                        @endif
                    </div>
                </a>

                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">
                        <a href="{{ route('house-tours.show', $tour->id) }}" class="hover:text-emerald-600 transition">
                            {{ $tour->title }}
                        </a>
                    </h3>
                    
                    @if($tour->property)
                    <p class="text-gray-600 text-sm flex items-center gap-1 mb-3">
                        <i class="fas fa-map-marker-alt text-emerald-500"></i>
                        {{ $tour->property->city }}, {{ $tour->property->state ?? 'Kenya' }}
                    </p>
                    @endif

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-eye"></i>
                            <span>{{ number_format($tour->views_count) }} views</span>
                        </div>
                        <a href="{{ route('house-tours.show', $tour->id) }}" 
                           class="text-emerald-600 hover:text-emerald-700 text-sm font-medium flex items-center gap-1">
                            Watch Now <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16">
                <i class="fas fa-video text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No House Tours Available</h3>
                <p class="text-gray-500">Check back soon for new property video tours.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($houseTours->hasPages())
        <div class="mt-12">
            {{ $houseTours->links() }}
        </div>
        @endif
    </div>
</div>
@endsection