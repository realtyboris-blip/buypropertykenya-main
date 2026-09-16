@extends('layouts.app')

@section('title', 'Properties for Sale and Rent in Kenya')

@section('content')
<!-- At the top of properties index -->
   {{-- =========================================================
     FILTER SECTION — Category, Type, Location, Price + Search
========================================================== --}}
    <section class="py-8" style="background:var(--bp-cream);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8" style="border:1px solid rgba(6,78,59,.06);">
                <div class="flex items-center gap-3 mb-5">
                    <span class="bp-eyebrow text-xs">Refine Your Search</span>
                    <span class="h-px flex-1 bg-gradient-to-r from-[var(--bp-gold)]/40 to-transparent"></span>
                </div>
                <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <!-- Category (Listing Type) -->
                    <div class="relative">
                        <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-[var(--bp-gold)] text-xs"></i>
                        <select name="listing_type" class="filter-select w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[var(--bp-gold)] focus:ring-2 focus:ring-[var(--bp-gold)]/20 transition">
                            <option value="">Category</option>
                            @foreach($listingTypes ?? [] as $type)
                            <option value="{{ $type->slug }}" {{ request('listing_type') == $type->slug ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type (Property Type) -->
                    <div class="relative">
                        <i class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-[var(--bp-gold)] text-xs"></i>
                        <select name="property_type" class="filter-select w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[var(--bp-gold)] focus:ring-2 focus:ring-[var(--bp-gold)]/20 transition">
                            <option value="">Property Type</option>
                            @foreach($propertyTypes ?? [] as $type)
                            <option value="{{ $type->slug }}" {{ request('property_type') == $type->slug ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-[var(--bp-gold)] text-xs"></i>
                        <select name="location" class="filter-select w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[var(--bp-gold)] focus:ring-2 focus:ring-[var(--bp-gold)]/20 transition">
                            <option value="">Location</option>
                            @foreach($locations ?? [] as $location)
                            <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Max Price -->
                    <div class="relative">
                        <i class="fas fa-coins absolute left-3 top-1/2 -translate-y-1/2 text-[var(--bp-gold)] text-xs"></i>
                        <select name="max_price" class="filter-select w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[var(--bp-gold)] focus:ring-2 focus:ring-[var(--bp-gold)]/20 transition">
                            <option value="">Max Price</option>
                            <option value="5000000" {{ request('max_price') == '5000000' ? 'selected' : '' }}>KSh 5M</option>
                            <option value="10000000" {{ request('max_price') == '10000000' ? 'selected' : '' }}>KSh 10M</option>
                            <option value="20000000" {{ request('max_price') == '20000000' ? 'selected' : '' }}>KSh 20M</option>
                            <option value="50000000" {{ request('max_price') == '50000000' ? 'selected' : '' }}>KSh 50M</option>
                            <option value="100000000" {{ request('max_price') == '100000000' ? 'selected' : '' }}>KSh 100M+</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <button type="submit"
                        class="bg-gradient-to-r from-[var(--bp-emerald-deep)] to-[var(--bp-emerald)] text-white px-6 py-2.5 rounded-xl font-medium text-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] flex items-center justify-center gap-2">
                        <i class="fas fa-search text-xs"></i> Search
                    </button>
                </form>

                <!-- Active Filters Display -->
                @php
                    $hasFilters = request()->hasAny(['listing_type', 'property_type', 'location', 'max_price']);
                @endphp
                @if($hasFilters)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-gray-500">Active Filters:</span>
                        @if(request('listing_type'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">
                            Category: {{ \App\Models\ListingType::where('slug', request('listing_type'))->first()?->name ?? request('listing_type') }}
                            <a href="{{ route('home') }}?{{ http_build_query(request()->except('listing_type')) }}" class="hover:text-emerald-900"><i class="fas fa-times text-[10px]"></i></a>
                        </span>
                        @endif
                        @if(request('property_type'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">
                            Type: {{ \App\Models\PropertyType::where('slug', request('property_type'))->first()?->name ?? request('property_type') }}
                            <a href="{{ route('home') }}?{{ http_build_query(request()->except('property_type')) }}" class="hover:text-emerald-900"><i class="fas fa-times text-[10px]"></i></a>
                        </span>
                        @endif
                        @if(request('location'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">
                            Location: {{ request('location') }}
                            <a href="{{ route('home') }}?{{ http_build_query(request()->except('location')) }}" class="hover:text-emerald-900"><i class="fas fa-times text-[10px]"></i></a>
                        </span>
                        @endif
                        @if(request('max_price'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700">
                            Max Price: KSh {{ number_format(request('max_price')) }}
                            <a href="{{ route('home') }}?{{ http_build_query(request()->except('max_price')) }}" class="hover:text-emerald-900"><i class="fas fa-times text-[10px]"></i></a>
                        </span>
                        @endif
                        <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">Clear All</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold gradient-text mb-4">All Properties</h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Discover your perfect property from our curated collection of premium listings</p>
        </div>
        
        <!-- Results Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($properties as $property)
            <div class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                <div class="relative h-64 overflow-hidden bg-gray-200">
                    <img src="{{ $property->getFirstImageAttribute() }}" alt="{{ $property->title }}"
                         class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="absolute top-3 left-3">
                        <span class="bg-gradient-to-r from-emerald-600 to-green-600 text-white px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg">
                            {{ $property->listingType?->name ?? 'Property' }}
                        </span>
                    </div>
                    @if($property->is_featured)
                    <div class="absolute top-3 right-3">
                        <span class="bg-amber-500 text-white px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg">
                            <i class="fas fa-star mr-1"></i>Featured
                        </span>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-xl text-gray-900 mb-2 line-clamp-1 group-hover:text-emerald-600 transition-colors">
                        {{ $property->title }}
                    </h3>
                    <p class="text-gray-600 text-sm mb-3 flex items-center gap-1">
                        <i class="fas fa-map-marker-alt text-emerald-600"></i> {{ $property->city }}
                    </p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-emerald-600">{{ $property->formatted_price }}</span>
                        <div class="flex gap-3 text-gray-500 text-sm">
                            @if($property->bedrooms)
                            <span><i class="fas fa-bed text-emerald-600"></i> {{ $property->bedrooms }}</span>
                            @endif
                            @if($property->bathrooms)
                            <span><i class="fas fa-bath text-emerald-600"></i> {{ $property->bathrooms }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('properties.show', $property->slug) }}" 
                       class="block text-center bg-gradient-to-r from-emerald-600 to-green-600 text-white px-4 py-3 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold group-hover:shadow-emerald-500/25">
                        View Details <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-home fa-4x text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No properties found.</p>
                <a href="{{ route('lead.form') }}" class="inline-block mt-4 text-emerald-600 hover:text-emerald-700">
                    Contact us for property inquiries
                </a>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $properties->links() }}
        </div>
    </div>
</div>
@endsection