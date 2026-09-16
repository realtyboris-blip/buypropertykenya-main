@props([
    'propertyTypes' => [],
    'listingTypes' => [],
    'locations' => [],
    'minPrice' => null,
    'maxPrice' => null,
])

<div class="bp-filter-wrapper mb-12" x-data="propertyFilter()" x-init="init()">
    <div class="bp-card rounded-2xl p-6 md:p-8" style="background:var(--bp-cream); border:1px solid rgba(6,78,59,.07);">
        <!-- Filter Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="bp-eyebrow" style="color:var(--bp-emerald-deep);">
                    <i class="fas fa-sliders-h mr-2"></i> Refine Your Search
                </span>
                <span class="h-px flex-1 w-12" style="background:linear-gradient(90deg,var(--bp-gold),transparent);"></span>
            </div>
            <button @click="resetFilters()" 
                    class="text-xs font-medium transition hover:opacity-70"
                    style="color:var(--bp-emerald);">
                <i class="fas fa-undo mr-1"></i> Reset
            </button>
        </div>

        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Location Filter -->
            <div class="relative group">
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:var(--bp-muted);">
                    <i class="fas fa-map-marker-alt mr-1" style="color:var(--bp-emerald);"></i> Location
                </label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color:var(--bp-gold);"></i>
                    <select name="location" 
                            class="bp-input w-full pl-9 pr-8 py-2.5 rounded-xl text-sm appearance-none cursor-pointer"
                            style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 12 12%22%3E%3Cpath fill=%22%23064e3b%22 d=%22M6 8L1 3h10z%22/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 12px center; background-size:10px;">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Property Type Filter -->
            <div class="relative group">
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:var(--bp-muted);">
                    <i class="fas fa-building mr-1" style="color:var(--bp-emerald);"></i> Property Type
                </label>
                <div class="relative">
                    <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color:var(--bp-gold);"></i>
                    <select name="property_type" 
                            class="bp-input w-full pl-9 pr-8 py-2.5 rounded-xl text-sm appearance-none cursor-pointer"
                            style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 12 12%22%3E%3Cpath fill=%22%23064e3b%22 d=%22M6 8L1 3h10z%22/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 12px center; background-size:10px;">
                        <option value="">All Types</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->slug }}" {{ request('property_type') == $type->slug ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Listing Type Filter -->
            <div class="relative group">
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:var(--bp-muted);">
                    <i class="fas fa-list mr-1" style="color:var(--bp-emerald);"></i> Listing Type
                </label>
                <div class="relative">
                    <i class="fas fa-clipboard-list absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color:var(--bp-gold);"></i>
                    <select name="listing_type" 
                            class="bp-input w-full pl-9 pr-8 py-2.5 rounded-xl text-sm appearance-none cursor-pointer"
                            style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 12 12%22%3E%3Cpath fill=%22%23064e3b%22 d=%22M6 8L1 3h10z%22/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 12px center; background-size:10px;">
                        <option value="">All Listings</option>
                        @foreach($listingTypes as $type)
                            <option value="{{ $type->slug }}" {{ request('listing_type') == $type->slug ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Price Range Filter -->
            <div class="relative group">
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:var(--bp-muted);">
                    <i class="fas fa-coins mr-1" style="color:var(--bp-emerald);"></i> Max Price
                </label>
                <div class="relative">
                    <i class="fas fa-currency-sign absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color:var(--bp-gold);"></i>
                    <select name="max_price" 
                            class="bp-input w-full pl-9 pr-8 py-2.5 rounded-xl text-sm appearance-none cursor-pointer"
                            style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 12 12%22%3E%3Cpath fill=%22%23064e3b%22 d=%22M6 8L1 3h10z%22/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 12px center; background-size:10px;">
                        <option value="">Any Budget</option>
                        <option value="5000000" {{ request('max_price') == '5000000' ? 'selected' : '' }}>KSh 5M</option>
                        <option value="10000000" {{ request('max_price') == '10000000' ? 'selected' : '' }}>KSh 10M</option>
                        <option value="20000000" {{ request('max_price') == '20000000' ? 'selected' : '' }}>KSh 20M</option>
                        <option value="50000000" {{ request('max_price') == '50000000' ? 'selected' : '' }}>KSh 50M</option>
                        <option value="100000000" {{ request('max_price') == '100000000' ? 'selected' : '' }}>KSh 100M+</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button - Full Width on Mobile -->
            <div class="sm:col-span-2 lg:col-span-4 flex justify-end mt-2">
                <button type="submit" 
                        class="bp-btn-primary px-8 py-2.5 rounded-xl font-semibold flex items-center gap-2 text-sm transition-all duration-300 hover:scale-105">
                    <i class="fas fa-search"></i> Apply Filters
                    <span class="hidden sm:inline ml-1 text-xs opacity-75">· {{ $propertiesCount ?? 0 }} properties</span>
                </button>
            </div>
        </form>

        <!-- Active Filters Display -->
        <div x-show="hasActiveFilters()" x-transition.duration.300 class="mt-4 pt-4 border-t" style="border-color:rgba(6,78,59,.1);">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-medium" style="color:var(--bp-muted);">Active Filters:</span>
                <template x-for="(filter, key) in activeFilters" :key="key">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium"
                          style="background:var(--bp-emerald-soft); color:var(--bp-emerald-deep);">
                        <span x-text="filter.label"></span>
                        <button @click="removeFilter(key)" class="hover:opacity-70 transition">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </span>
                </template>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bp-filter-wrapper select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    .bp-filter-wrapper select option {
        padding: 8px;
    }
    
    .bp-filter-wrapper .bp-input:focus {
        outline: none;
        border-color: var(--bp-emerald);
        box-shadow: 0 0 0 3px rgba(13, 122, 95, 0.12);
    }
</style>
@endpush

@push('scripts')
<script>
function propertyFilter() {
    return {
        activeFilters: [],
        
        init() {
            this.updateActiveFilters();
        },
        
        updateActiveFilters() {
            this.activeFilters = [];
            const params = new URLSearchParams(window.location.search);
            
            const filters = [
                { key: 'location', label: 'Location: ' },
                { key: 'property_type', label: 'Type: ' },
                { key: 'listing_type', label: 'Listing: ' },
                { key: 'max_price', label: 'Max Price: ' }
            ];
            
            filters.forEach(filter => {
                const value = params.get(filter.key);
                if (value) {
                    this.activeFilters.push({
                        key: filter.key,
                        label: filter.label + value
                    });
                }
            });
        },
        
        hasActiveFilters() {
            return this.activeFilters.length > 0;
        },
        
        removeFilter(key) {
            const params = new URLSearchParams(window.location.search);
            params.delete(key);
            window.location.href = window.location.pathname + '?' + params.toString();
        },
        
        resetFilters() {
            window.location.href = window.location.pathname;
        }
    }
}
</script>
@endpush
