@extends('layouts.app')

@section('title', 'BuyProperty Kenya — Premium Properties Across Kenya')
@section('description', 'Discover Kenya\'s finest homes, villas, and commercial spaces. Curated, verified, and presented by BuyProperty Kenya.')

@section('content')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

<div class="bp-home">

    {{-- =========================================================
     HERO SLIDER — Reduced height, smooth sliding
========================================================== --}}
    <section class="relative w-full overflow-hidden" style="height:75vh; min-height:580px; max-height:750px; background:var(--bp-emerald-deep);">
        <div class="absolute inset-0" x-data="imageSlider({{ $featuredPropertiesForSlider ?? [] }})" x-init="init()">
            <template x-for="(property, index) in properties" :key="index">
                <div class="absolute inset-0 transition-all duration-1000 ease-in-out"
                    :class="currentSlide === index ? 'opacity-100 z-10 scale-100' : 'opacity-0 z-0 scale-105'">
                    <div class="absolute inset-0 bg-cover bg-center"
                        :style="'background-image:linear-gradient(105deg, rgba(6,29,24,.82) 0%, rgba(6,29,24,.55) 45%, rgba(6,29,24,.15) 100%), url(' + property.image + ');'">
                    </div>

                    <div class="absolute inset-0 flex items-center">
                        <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-16 w-full">
                            <div class="max-w-2xl slide-in" style="animation-delay: 0.3s;">
                                <div class="flex items-center mb-4">
                                    <span class="bp-eyebrow" style="color:var(--bp-gold-soft);" x-text="property.badge"></span>
                                    <span class="bp-gold-rule"></span>
                                    <span class="text-white/60 text-xs tracking-[.32em] uppercase font-light">Kenya</span>
                                </div>

                                <h1 class="bp-display text-white text-4xl md:text-6xl lg:text-7xl font-light leading-[1.02] mb-4"
                                    style="text-shadow: 0 2px 30px rgba(6,78,59,0.3);">
                                    <span x-text="property.title"></span>
                                </h1>

                                <p class="text-white/80 text-base md:text-lg leading-relaxed max-w-lg mb-6 font-light tracking-wide"
                                    x-text="property.catchyPhrase"></p>

                                <div class="flex flex-wrap gap-3">
                                    <a :href="property.detailsUrl"
                                        class="inline-flex items-center gap-2 px-7 py-3 rounded-full text-sm tracking-wide btn-glow transition-all duration-300"
                                        style="background:linear-gradient(135deg,var(--bp-gold),var(--bp-gold-soft)); color:var(--bp-emerald-deep); font-weight:600; box-shadow:0 8px 22px -10px rgba(201,168,76,.6);">
                                        <span class="truncate max-w-[140px]">Explore Residence</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1.5 transition-transform duration-300"></i>
                                    </a>
                                    <a href="{{ route('lead.form') }}"
                                        class="inline-flex items-center gap-2 px-7 py-3 rounded-full text-sm font-medium tracking-wide btn-ghost-glow transition-all duration-300"
                                        style="border:1.5px solid rgba(255,255,255,.5); color:var(--bp-gold-soft); backdrop-filter:blur(6px); background:rgba(255,255,255,.06);">
                                        <span class="truncate max-w-[140px]">Schedule Viewing</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Property number --}}
                    <div class="hidden lg:flex absolute right-12 bottom-16 z-20 text-white/15">
                        <span class="bp-display text-7xl font-light" x-text="String(index + 1).padStart(2, '0')"></span>
                    </div>
                </div>
            </template>

            {{-- side meta rail --}}
            <div class="hidden lg:flex absolute right-10 top-1/2 -translate-y-1/2 z-30 flex-col items-center gap-4">
                <span class="text-white/25 text-[10px] tracking-[.4em] uppercase rotate-90 origin-center font-light" style="writing-mode:vertical-rl;">Curated Collection</span>
                <div class="w-px h-16 bg-gradient-to-b from-transparent via-[var(--bp-gold)] to-transparent"></div>
            </div>

            {{-- indicators --}}
            <div class="absolute bottom-8 md:bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-2.5 items-center">
                <template x-for="(property, index) in properties" :key="index">
                    <button @click="goToSlide(index)"
                        class="rounded-full transition-all duration-500"
                        :class="currentSlide === index
                            ? 'w-8 h-[3px] bg-[var(--bp-gold-soft)]'
                            : 'w-5 h-[2px] bg-white/30 hover:bg-white/60'">
                    </button>
                </template>
            </div>

            {{-- arrows --}}
            <button @click="prevSlide()"
                class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full border border-white/20 text-white/50 hover:text-white hover:border-[var(--bp-gold)] flex items-center justify-center transition-all duration-300 hover:bg-white/10">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <button @click="nextSlide()"
                class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full border border-white/20 text-white/50 hover:text-white hover:border-[var(--bp-gold)] flex items-center justify-center transition-all duration-300 hover:bg-white/10">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>

            {{-- progress bar --}}
            <div class="absolute bottom-0 left-0 z-30 h-[2px]"
                style="background:linear-gradient(90deg,var(--bp-gold),var(--bp-gold-soft));transition:width 6s linear;"
                :style="'width:' + progressWidth"></div>
        </div>
    </section>

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

    {{-- =========================================================
     FEATURED PROPERTIES
========================================================== --}}
    <section class="py-16 md:py-20" style="background:var(--bp-cream);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
                <div>
                    <span class="bp-eyebrow text-xs">Featured Collection</span>
                    <h2 class="bp-display text-3xl md:text-4xl font-light mt-2" style="color:var(--bp-emerald-deep);">
                        <span class="font-medium">Residences</span> of <em class="not-italic" style="color:var(--bp-gold);">distinction</em>
                    </h2>
                    <p class="mt-2 text-sm max-w-xl" style="color:var(--bp-muted);">
                        A handpicked selection of Kenya's most desirable homes, estates, and investments — verified and ready to view.
                    </p>
                </div>
                <a href="{{ route('properties.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium tracking-wide group"
                    style="color:var(--bp-emerald-deep);">
                    View All Properties
                    <span class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 group-hover:bg-[var(--bp-gold)] group-hover:text-white"
                        style="border:1px solid var(--bp-emerald);">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($featuredProperties ?? [] as $property)
                <article class="bp-card rounded-2xl overflow-hidden flex flex-col group">
                    <div class="relative overflow-hidden h-64 md:h-72">
                        <img src="{{ $property->getFirstImageAttribute() }}" alt="{{ $property->title }}"
                            class="w-full h-full object-cover transition duration-[800ms] ease-out group-hover:scale-105"
                            onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="bp-chip bp-chip-emerald text-[10px]">{{ $property->listingType?->name ?? 'For Sale' }}</span>
                            @if($property->is_featured)
                            <span class="bp-chip bp-chip-gold text-[10px]"><i class="fas fa-star text-[8px]"></i> Featured</span>
                            @endif
                        </div>

                        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
                            <div class="text-white">
                                <p class="text-[9px] tracking-[.25em] uppercase text-white/50 font-light">Price</p>
                                <p class="bp-display text-xl font-semibold leading-tight">{{ $property->formatted_price }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="bp-display text-lg font-medium bp-line-clamp-1" style="color:var(--bp-emerald-deep);">
                            {{ $property->title }}
                        </h3>
                        <p class="mt-1 text-sm flex items-center gap-2" style="color:var(--bp-muted);">
                            <i class="fas fa-map-marker-alt text-[10px]" style="color:var(--bp-gold);"></i>
                            {{ $property->city }}, {{ $property->state ?? 'Kenya' }}
                        </p>

                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-dashed text-xs"
                            style="border-color:rgba(6,78,59,.1); color:var(--bp-muted);">
                            <span class="flex items-center gap-1.5"><i class="fas fa-bed text-[var(--bp-emerald)] text-[10px]"></i> {{ $property->bedrooms ?? 0 }} Beds</span>
                            <span class="flex items-center gap-1.5"><i class="fas fa-bath text-[var(--bp-emerald)] text-[10px]"></i> {{ $property->bathrooms ?? 0 }} Baths</span>
                            @if($property->area_sqft)
                            <span class="flex items-center gap-1.5"><i class="fas fa-vector-square text-[var(--bp-emerald)] text-[10px]"></i> {{ number_format($property->area_sqft) }}</span>
                            @endif
                        </div>

                        <a href="{{ route('properties.show', $property->slug) }}"
                            class="mt-4 inline-flex items-center justify-between px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 group-hover:bg-[var(--bp-gold)] group-hover:text-white"
                            style="background:var(--bp-emerald-soft); color:var(--bp-emerald-deep);">
                            View Details
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </article>
                @empty
                <div class="col-span-3 text-center py-12" style="color:var(--bp-muted);">
                    <p>No featured properties available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- =========================================================
     STATISTICS
========================================================== --}}
    <section class="relative py-14 md:py-16 overflow-hidden" style="background:var(--bp-emerald-deep);">
        <div class="absolute inset-0 bp-noise opacity-30"></div>
        <div class="absolute -top-32 -left-32 w-64 h-64 rounded-full filter blur-3xl" style="background:rgba(201,168,76,.1);"></div>
        <div class="absolute -bottom-32 -right-32 w-64 h-64 rounded-full filter blur-3xl" style="background:rgba(13,122,95,.2);"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                @php
                $stats = [
                ['v'=>$totalProperties ?? 0,'l'=>'Properties Listed'],
                ['v'=>$totalPropertiesSold ?? 0,'l'=>'Properties Sold'],
                ['v'=>$totalAgents ?? 0,'l'=>'Expert Agents'],
                ['v'=>$happyClients ?? 0,'l'=>'Happy Clients'],
                ];
                @endphp
                @foreach($stats as $s)
                <div>
                    <div class="bp-display text-3xl md:text-4xl font-light" style="color:var(--bp-gold-soft);">
                        {{ number_format($s['v']) }}+
                    </div>
                    <div class="mt-1 text-[9px] tracking-[.3em] uppercase text-white/40 font-light">{{ $s['l'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================================================
     WHY CHOOSE US
========================================================== --}}
    <section class="py-16 md:py-20" style="background:#fff;">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="bp-eyebrow text-xs">Why Choose Us</span>
                <h2 class="bp-display text-3xl md:text-4xl font-light mt-3" style="color:var(--bp-emerald-deep);">
                    <span class="font-medium">Premium Service,</span> <em class="not-italic" style="color:var(--bp-gold);">End to End</em>
                </h2>
                <p class="mt-2 text-sm" style="color:var(--bp-muted);">
                    From first viewing to final signature — every detail handled with the discretion you expect.
                </p>
                <div class="w-10 h-px mx-auto mt-4" style="background:linear-gradient(90deg,transparent,var(--bp-gold),transparent);"></div>
            </div>

            @php
            $perks = [
            ['icon'=>'fa-shield-halved','t'=>'Verified & Secure','d'=>'Every listing is vetted. Transactions are protected with bank-grade safeguards.'],
            ['icon'=>'fa-user-tie','t'=>'Dedicated Advisors','d'=>'Personal property advisors guide you through search, negotiation, and closing.'],
            ['icon'=>'fa-chart-line','t'=>'Market Intelligence','d'=>'Data-driven valuations ensure you transact at the right price.'],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($perks as $p)
                <div class="group p-8 rounded-2xl transition-all duration-500 hover:-translate-y-1"
                    style="background:var(--bp-cream); border:1px solid rgba(6,78,59,.06);">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 group-hover:scale-105"
                        style="background:linear-gradient(135deg,var(--bp-emerald-deep),var(--bp-emerald)); color:var(--bp-gold-soft); box-shadow:0 12px 24px -12px rgba(6,78,59,.4);">
                        <i class="fas {{ $p['icon'] }} text-lg"></i>
                    </div>
                    <h3 class="bp-display text-xl font-medium mb-1.5" style="color:var(--bp-emerald-deep);">{{ $p['t'] }}</h3>
                    <p class="text-sm leading-relaxed" style="color:var(--bp-muted);">{{ $p['d'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================================================
     GOOGLE REVIEWS
========================================================== --}}
    <section class="relative py-16 md:py-20 overflow-hidden" style="background:var(--bp-cream-deep);">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full filter blur-3xl" style="background:rgba(13,122,95,.06);"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full filter blur-3xl" style="background:rgba(201,168,76,.08);"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="bp-eyebrow text-xs"><i class="fab fa-google mr-2" style="color:var(--bp-emerald);"></i> Verified Reviews</span>
                <h2 class="bp-display text-3xl md:text-4xl font-light mt-3" style="color:var(--bp-emerald-deep);">
                    Voices of our <em class="not-italic font-medium" style="color:var(--bp-gold);">clientele</em>
                </h2>
            </div>

            @php
            $reviews = [
            ['n'=>'John Mwangi','m'=>'Nairobi · Verified Buyer','t'=>'Excellent service. Found my dream apartment in Kilimani within a week. The advisors were professional and discreet.','d'=>'2 weeks ago'],
            ['n'=>'Sarah Kimani','m'=>'Mombasa · Homeowner','t'=>'A seamless transaction from start to finish. Highly recommended for anyone seeking property along the coast.','d'=>'1 month ago'],
            ['n'=>'Michael Otieno','m'=>'Kisumu · Investor','t'=>'Outstanding variety and sharp market knowledge. Secured a strong investment in a high-growth corridor.','d'=>'3 weeks ago'],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($reviews as $r)
                <div class="bp-card rounded-2xl p-6 relative flex flex-col">
                    <i class="fas fa-quote-left absolute top-5 right-6 text-3xl opacity-10" style="color:var(--bp-gold);"></i>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm flex-shrink-0"
                            style="background:linear-gradient(135deg,var(--bp-emerald-deep),var(--bp-emerald)); color:var(--bp-gold-soft);">
                            {{ strtoupper(substr($r['n'],0,1)) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm" style="color:var(--bp-emerald-deep);">{{ $r['n'] }}</h4>
                            <p class="text-xs" style="color:var(--bp-muted);">{{ $r['m'] }}</p>
                        </div>
                    </div>
                    <div class="flex mb-2 text-[10px]" style="color:var(--bp-gold);">
                        @for($i=0;$i<5;$i++)<i class="fas fa-star"></i>@endfor
                    </div>
                    <p class="text-sm leading-relaxed flex-1" style="color:#3d4a44; line-height: 1.7;">"{{ $r['t'] }}"</p>
                    <div class="flex items-center justify-between text-xs pt-3 mt-3 border-t border-dashed" style="border-color:rgba(6,78,59,.08); color:var(--bp-muted);">
                        <span class="flex items-center gap-1.5"><i class="fab fa-google" style="color:var(--bp-emerald);"></i> Google Review</span>
                        <span>{{ $r['d'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================================================
     CALL TO ACTION
========================================================== --}}
    <section class="relative py-14 md:py-16 overflow-hidden" style="background:var(--bp-emerald-deep);">
        <div class="absolute inset-0 bp-noise opacity-30"></div>
        <div class="absolute top-0 left-0 w-56 h-56 rounded-full filter blur-3xl" style="background:rgba(201,168,76,.1);"></div>
        <div class="absolute bottom-0 right-0 w-56 h-56 rounded-full filter blur-3xl" style="background:rgba(13,122,95,.15);"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <span class="bp-eyebrow text-xs" style="color:var(--bp-gold-soft);">Begin Your Search</span>
            <h2 class="bp-display text-3xl md:text-5xl font-light text-white mt-3 leading-[1.05]">
                Your next address <br class="hidden md:block"/>
                <em class="not-italic font-medium" style="color:var(--bp-gold-soft);">awaits.</em>
            </h2>
            <p class="mt-3 text-white/50 text-sm max-w-xl mx-auto font-light leading-relaxed">
                Speak with a senior advisor for a confidential consultation, or browse our curated collection at your leisure.
            </p>
            <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('lead.form') }}"
                    class="bp-btn-gold inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full text-sm tracking-wide transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl">
                    <i class="fas fa-paper-plane text-xs"></i> Request a Consultation
                </a>
                <a href="{{ route('properties.index') }}"
                    class="bp-btn-ghost inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full text-sm font-medium tracking-wide transition-all duration-300 hover:scale-[1.02]">
                    <i class="fas fa-compass text-xs"></i> Browse the Collection
                </a>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
    function imageSlider(properties) {
        return {
            properties: properties || [],
            currentSlide: 0,
            autoplayInterval: null,
            progressWidth: '0%',
            intervalMs: 5000,

            init() {
                console.log('Slider initialized with', this.properties.length, 'properties');
                if (this.properties.length > 1) {
                    this.startAutoplay();
                } else if (this.properties.length === 1) {
                    this.currentSlide = 0;
                    this.progressWidth = '100%';
                }
            },

            goToSlide(index) {
                if (index < 0 || index >= this.properties.length) return;
                this.currentSlide = index;
                this.resetAutoplay();
            },

            nextSlide() {
                if (this.properties.length === 0) return;
                this.currentSlide = (this.currentSlide + 1) % this.properties.length;
                this.resetAutoplay();
            },

            prevSlide() {
                if (this.properties.length === 0) return;
                this.currentSlide = (this.currentSlide - 1 + this.properties.length) % this.properties.length;
                this.resetAutoplay();
            },

            startAutoplay() {
                this.resetProgressBar();
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                }
                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, this.intervalMs);
            },

            resetAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }
                if (this.properties.length > 1) {
                    this.resetProgressBar();
                    this.startAutoplay();
                }
            },

            resetProgressBar() {
                this.progressWidth = '0%';
                setTimeout(() => {
                    this.progressWidth = '100%';
                }, 50);
            }
        };
    }
</script>
@endpush

@endsection