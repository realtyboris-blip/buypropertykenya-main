<nav x-data="{ mobileMenuOpen: false, searchOpen: false, forSaleOpen: false, forRentOpen: false, listingsOpen: false }" class="bp-nav mb-4">
    <div class="max-w-7xl ml-0  px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo - Larger -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}"
                            alt="BuyProperty Kenya"
                            class="h-10 md:h-12 lg:h-20 w-auto object-contain">
                    </a>
                </div>
            </div>
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 transition duration-300 uppercase tracking-wide text-sm font-semibold">Home</a>

                <!-- FOR SALE Dropdown with Property Types + Locations -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-gray-700 hover:text-emerald-600 transition duration-300 flex items-center gap-2 uppercase tracking-wide text-sm font-semibold">
                        For Sale <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" x-transition.duration.200ms class="absolute left-0 mt-2 w-80 bg-white rounded-xl shadow-2xl py-3 z-50 border border-gray-100">
                        <a href="{{ route('properties.index', ['listing_type' => 'sale']) }}" class="block px-5 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition font-semibold border-b border-gray-100">
                            All For Sale
                        </a>
                        @php
                        $propertyTypes = \App\Models\PropertyType::where('is_active', true)->orderBy('name')->get();
                        $saleType = \App\Models\ListingType::where('slug', 'sale')->first();
                        $locations = \App\Models\Property::where('status', 'available')
                        ->when($saleType, function($q) use ($saleType) {
                        return $q->where('listing_type_id', $saleType->id);
                        })
                        ->select('city')
                        ->distinct()
                        ->pluck('city');
                        @endphp
                        @foreach($propertyTypes as $type)
                        <div class="group relative">
                            <div class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition flex justify-between items-center cursor-pointer">
                                <span>{{ $type->name }} For Sale</span>
                                <i class="fas fa-chevron-right text-xs text-gray-400 group-hover:text-emerald-600"></i>
                            </div>
                            <div class="absolute left-full top-0 ml-1 w-64 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100 hidden group-hover:block">
                                <a href="{{ route('properties.index', ['listing_type' => 'sale', 'property_type' => $type->slug]) }}"
                                    class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition font-semibold border-b border-gray-100">
                                    All Locations
                                </a>
                                @foreach($locations as $location)
                                <a href="{{ route('properties.index', ['listing_type' => 'sale', 'property_type' => $type->slug, 'location' => $location]) }}"
                                    class="block px-5 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                    {{ $location }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- FOR RENT Dropdown with Property Types + Locations -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-gray-700 hover:text-emerald-600 transition duration-300 flex items-center gap-2 uppercase tracking-wide text-sm font-semibold">
                        For Rent <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" x-transition.duration.200ms class="absolute left-0 mt-2 w-80 bg-white rounded-xl shadow-2xl py-3 z-50 border border-gray-100">
                        <a href="{{ route('properties.index', ['listing_type' => 'rent']) }}" class="block px-5 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition font-semibold border-b border-gray-100">
                            All For Rent
                        </a>
                        @php
                        $rentType = \App\Models\ListingType::where('slug', 'rent')->first();
                        $rentLocations = \App\Models\Property::where('status', 'available')
                        ->when($rentType, function($q) use ($rentType) {
                        return $q->where('listing_type_id', $rentType->id);
                        })
                        ->select('city')
                        ->distinct()
                        ->pluck('city');
                        @endphp
                        @foreach($propertyTypes as $type)
                        <div class="group relative">
                            <div class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition flex justify-between items-center cursor-pointer">
                                <span>{{ $type->name }} For Rent</span>
                                <i class="fas fa-chevron-right text-xs text-gray-400 group-hover:text-emerald-600"></i>
                            </div>
                            <div class="absolute left-full top-0 ml-1 w-64 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100 hidden group-hover:block">
                                <a href="{{ route('properties.index', ['listing_type' => 'rent', 'property_type' => $type->slug]) }}"
                                    class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition font-semibold border-b border-gray-100">
                                    All Locations
                                </a>
                                @foreach($rentLocations as $location)
                                <a href="{{ route('properties.index', ['listing_type' => 'rent', 'property_type' => $type->slug, 'location' => $location]) }}"
                                    class="block px-5 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                    {{ $location }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- LISTINGS Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-gray-700 hover:text-emerald-600 transition duration-300 flex items-center gap-2 uppercase tracking-wide text-sm font-semibold">
                        Listings <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" x-transition.duration.200ms class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-2xl py-3 z-50 border border-gray-100">
                        <a href="{{ route('properties.index') }}" class="block px-5 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition border-b border-gray-100">
                            All Properties
                        </a>
                        @php
                        $listingTypes = \App\Models\ListingType::where('is_active', true)->orderBy('name')->get();
                        @endphp
                        @foreach($listingTypes as $listing)
                        <a href="{{ route('properties.index', ['listing_type' => $listing->slug]) }}"
                            class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                            {{ $listing->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                <!-- MEDIA Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="bp-nav-link flex items-center gap-2">
                        Media <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open" x-transition.duration.200ms class="bp-dropdown absolute left-0 mt-2 w-64">
                        <a href="{{ route('house-tours.index') }}" class="bp-dropdown-link flex items-center gap-3">
                            <i class="fas fa-video text-emerald-600 w-5"></i>
                            <span>House Tours</span>
                        </a>
                        <a href="{{ route('podcasts.index') }}" class="bp-dropdown-link flex items-center gap-3">
                            <i class="fas fa-podcast text-emerald-600 w-5"></i>
                            <span>Podcasts</span>
                        </a>
                        <a href="{{ route('faqs.index') }}" class="bp-dropdown-link flex items-center gap-3">
                            <i class="fas fa-question-circle text-emerald-600 w-5"></i>
                            <span>FAQs</span>
                        </a>
                        <a href="{{ route('area-guides.index') }}" class="bp-dropdown-link flex items-center gap-3">
                            <i class="fas fa-map-marked-alt text-emerald-600 w-5"></i>
                            <span>Area Guides</span>
                        </a>
                    </div>
                </div>

                <!-- PROJECTS Dropdown with submenus -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="bp-nav-link flex items-center gap-2">
                        Projects <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open" x-transition.duration.200ms class="bp-dropdown absolute left-0 mt-2 w-56">
                        <a href="{{ route('projects.index') }}" class="bp-dropdown-link font-semibold border-b border-gray-100">
                            <i class="fas fa-th-large text-emerald-600 w-5"></i>
                            All Projects
                        </a>
                        <a href="{{ route('projects.ongoing') }}" class="bp-dropdown-link">
                            <span class="w-2 h-2 rounded-full bg-amber-500 inline-block mr-2"></span>
                            Ongoing
                        </a>
                        <a href="{{ route('projects.completed') }}" class="bp-dropdown-link">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-2"></span>
                            Completed
                        </a>
                        <a href="{{ route('projects.off-plan') }}" class="bp-dropdown-link">
                            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block mr-2"></span>
                            Off-Plan
                        </a>
                    </div>
                </div>
                <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-emerald-600 transition duration-300 uppercase tracking-wide text-sm font-semibold">Blog</a>
                <a href="{{ route('lead.form') }}" class="text-gray-700 hover:text-emerald-600 transition duration-300 uppercase tracking-wide text-sm font-semibold">Contact</a>
            </div>

            <!-- Right Section - Increased spacing -->
            <!-- <div class="hidden md:flex items-center space-x-8"> -->
            <!-- Search Toggle -->
            <!-- <button @click="searchOpen = !searchOpen" class="text-gray-600 hover:text-emerald-600 transition">
                    <i class="fas fa-search text-xl"></i>
                </button> -->

            <!-- @auth -->
            <!-- User Dropdown -->
            <!-- <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" class="flex items-center space-x-2 text-gray-700 hover:text-emerald-600">
                        <i class="fas fa-user-circle text-2xl"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div x-show="userOpen" @click.away="userOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div> -->
            <!-- @else -->
            <!-- <a href="{{ route('login') }}" class="text-gray-600 hover:text-emerald-600 transition uppercase tracking-wide text-sm font-semibold">Login</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-emerald-600 to-green-600 text-white px-5 py-2 rounded-lg hover:shadow-lg transition text-sm font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>Register
                </a> -->
            <!-- @endauth -->
            <!-- </div> -->

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu with Accordion -->
        <div x-show="mobileMenuOpen" x-transition.duration.300ms class="md:hidden py-4 border-t">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg uppercase tracking-wide text-sm font-semibold">Home</a>

                <!-- FOR SALE Mobile Accordion -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full text-left text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg flex justify-between items-center uppercase tracking-wide text-sm font-semibold">
                        For Sale <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-4 space-y-2">
                        <a href="{{ route('properties.index', ['listing_type' => 'sale']) }}" class="block text-gray-600 hover:text-emerald-600 transition px-3 py-2 text-sm font-semibold border-l-2 border-emerald-500">All For Sale</a>
                        @foreach($propertyTypes as $type)
                        <div x-data="{ subOpen: false }">
                            <button @click="subOpen = !subOpen" class="w-full text-left text-gray-600 hover:text-emerald-600 transition px-3 py-2 text-sm flex justify-between items-center">
                                {{ $type->name }} For Sale <i class="fas fa-chevron-right text-xs transition-transform" :class="{'rotate-90': subOpen}"></i>
                            </button>
                            <div x-show="subOpen" x-collapse class="pl-4 space-y-1">
                                <a href="{{ route('properties.index', ['listing_type' => 'sale', 'property_type' => $type->slug]) }}"
                                    class="block text-gray-500 hover:text-emerald-600 transition px-3 py-1 text-xs">
                                    All Locations
                                </a>
                                @foreach($locations as $location)
                                <a href="{{ route('properties.index', ['listing_type' => 'sale', 'property_type' => $type->slug, 'location' => $location]) }}"
                                    class="block text-gray-500 hover:text-emerald-600 transition px-3 py-1 text-xs">
                                    {{ $location }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- FOR RENT Mobile Accordion -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full text-left text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg flex justify-between items-center uppercase tracking-wide text-sm font-semibold">
                        For Rent <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-4 space-y-2">
                        <a href="{{ route('properties.index', ['listing_type' => 'rent']) }}" class="block text-gray-600 hover:text-emerald-600 transition px-3 py-2 text-sm font-semibold border-l-2 border-emerald-500">All For Rent</a>
                        @foreach($propertyTypes as $type)
                        <div x-data="{ subOpen: false }">
                            <button @click="subOpen = !subOpen" class="w-full text-left text-gray-600 hover:text-emerald-600 transition px-3 py-2 text-sm flex justify-between items-center">
                                {{ $type->name }} For Rent <i class="fas fa-chevron-right text-xs transition-transform" :class="{'rotate-90': subOpen}"></i>
                            </button>
                            <div x-show="subOpen" x-collapse class="pl-4 space-y-1">
                                <a href="{{ route('properties.index', ['listing_type' => 'rent', 'property_type' => $type->slug]) }}"
                                    class="block text-gray-500 hover:text-emerald-600 transition px-3 py-1 text-xs">
                                    All Locations
                                </a>
                                @foreach($rentLocations as $location)
                                <a href="{{ route('properties.index', ['listing_type' => 'rent', 'property_type' => $type->slug, 'location' => $location]) }}"
                                    class="block text-gray-500 hover:text-emerald-600 transition px-3 py-1 text-xs">
                                    {{ $location }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- LISTINGS Mobile Accordion -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full text-left text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg flex justify-between items-center uppercase tracking-wide text-sm font-semibold">
                        Listings <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-4 space-y-2">
                        <a href="{{ route('properties.index') }}" class="block text-gray-600 hover:text-emerald-600 transition px-3 py-2 text-sm">All Properties</a>
                        @foreach($listingTypes as $listing)
                        <a href="{{ route('properties.index', ['listing_type' => $listing->slug]) }}"
                            class="block text-gray-600 hover:text-emerald-600 transition px-3 py-2 text-sm">
                            {{ $listing->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="/projects" class="text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg uppercase tracking-wide text-sm font-semibold">Projects</a>
                <a href="/agents" class="text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg uppercase tracking-wide text-sm font-semibold">Agents</a>
                <a href="{{ route('lead.form') }}" class="text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg uppercase tracking-wide text-sm font-semibold">Contact</a>

                <!-- @auth
                <div class="border-t border-gray-100 pt-2 mt-2">
                    <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg">Logout</button>
                    </form>
                </div>
                @else
                <div class="border-t border-gray-100 pt-2 mt-2">
                    <a href="{{ route('login') }}" class="block text-gray-700 hover:text-emerald-600 transition px-3 py-2 rounded-lg uppercase tracking-wide text-sm font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="block bg-gradient-to-r from-emerald-600 to-green-600 text-white px-4 py-2 rounded-lg text-center mt-2 text-sm font-semibold">Register</a>
                </div>
                @endauth -->
            </div>
        </div>
    </div>

    <!-- Search Bar Dropdown -->
    <!-- <div x-show="searchOpen" x-transition.duration.300ms class="bg-white border-t shadow-lg py-6">
        <div class="max-w-7xl mx-auto px-4">
            <form action="{{ route('properties.index') }}" method="GET" class="max-w-3xl mx-auto">
                <div class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" placeholder="Search by city, location or property name..."
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div> -->
</nav>