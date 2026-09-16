@extends('layouts.app')

@section('title', $project->name . ' - Project Details')
@section('description', $project->description)

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('projects.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to Projects
            </a>
        </div>

        <!-- Project Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="relative h-96">
                <img src="{{ $project->cover_image }}" alt="{{ $project->name }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($project->status == 'ongoing')
                    <span class="bg-amber-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                        🔄 Ongoing
                    </span>
                    @elseif($project->status == 'completed')
                    <span class="bg-emerald-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                        ✅ Completed
                    </span>
                    @else
                    <span class="bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                        📋 Off-Plan
                    </span>
                    @endif
                </div>

                <!-- Featured Badge -->
                @if($project->is_featured)
                <div class="absolute top-4 left-4">
                    <span class="bg-amber-400 text-amber-900 px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                        ⭐ Featured
                    </span>
                </div>
                @endif

                <!-- Content overlay -->
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <h1 class="text-4xl font-bold mb-2">{{ $project->name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $project->location }}, {{ $project->city }}</span>
                        <span><i class="fas fa-building mr-1"></i> {{ $project->developer }}</span>
                    </div>
                </div>
            </div>

            <!-- Project Details -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <p class="text-sm text-gray-500">Starting Price</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $project->formatted_starting_price }}</p>
                    </div>
                    @if($project->max_price)
                    <div>
                        <p class="text-sm text-gray-500">Max Price</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $project->formatted_max_price }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
                            style="background: {{ $project->status == 'ongoing' ? '#fef3c7' : ($project->status == 'completed' ? '#d1fae5' : '#dbeafe') }};
                                     color: {{ $project->status == 'ongoing' ? '#92400e' : ($project->status == 'completed' ? '#065f46' : '#1e40af') }}">
                            {{ $project->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">About This Project</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $project->description }}</p>
                </div>

                <!-- Progress (for ongoing projects) -->
                @if($project->status == 'ongoing')
                <div class="mb-8 p-6 bg-amber-50 rounded-xl border border-amber-100">
                    <h3 class="font-semibold text-amber-800 mb-2">📊 Project Progress</h3>
                    <div class="flex justify-between text-sm text-amber-700 mb-1">
                        <span>{{ $project->progress_percentage }}% Complete</span>
                        <span>{{ $project->available_units ?? 0 }} units available</span>
                    </div>
                    <div class="w-full bg-amber-200 rounded-full h-2.5">
                        <div class="bg-amber-500 h-2.5 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                    </div>
                </div>
                @endif

                <!-- Amenities -->
                @php
                    $amenities = is_string($project->amenities) ? json_decode($project->amenities, true) : $project->amenities;
                @endphp
                @if($amenities && count($amenities) > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">🏷️ Amenities</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($amenities as $amenity)
                        <span class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm flex items-center gap-1">
                            <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                            {{ is_array($amenity) ? ($amenity['amenity'] ?? reset($amenity)) : $amenity }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Floor Plans -->
                @php
                    $floorPlans = is_string($project->floor_plans) ? json_decode($project->floor_plans, true) : $project->floor_plans;
                @endphp
                @if($floorPlans && count($floorPlans) > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">📐 Floor Plans</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($floorPlans as $floorplan)
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            @if(isset($floorplan['image']))
                            <img src="{{ asset('storage/' . $floorplan['image']) }}"
                                alt="{{ $floorplan['name'] ?? 'Floor Plan' }}"
                                class="w-full h-32 object-cover rounded-lg mb-2">
                            @endif
                            <h4 class="font-semibold">{{ $floorplan['name'] ?? 'Floor Plan' }}</h4>
                            <p class="text-sm text-gray-600">{{ $floorplan['area'] ?? '' }}</p>
                            <p class="text-sm font-medium text-emerald-600">{{ $floorplan['price'] ?? '' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Gallery -->
                @php
                    $gallery = is_string($project->gallery) ? json_decode($project->gallery, true) : $project->gallery;
                @endphp
                @if($gallery && count($gallery) > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">🖼️ Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($gallery as $image)
                        <div class="rounded-lg overflow-hidden h-40 group cursor-pointer">
                            <img src="{{ asset('storage/' . $image) }}"
                                alt="{{ $project->name }}"
                                class="w-full h-full object-cover hover:scale-110 transition duration-500">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- CTA -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-gray-200">
                    <a href="{{ route('lead.form') }}?project={{ $project->slug }}"
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white px-8 py-3 rounded-xl hover:shadow-xl transition font-semibold">
                        <i class="fas fa-envelope"></i> Request Information
                    </a>
                    <a href="{{ route('properties.index') }}?city={{ $project->city }}"
                        class="inline-flex items-center justify-center gap-2 border-2 border-emerald-600 text-emerald-600 px-8 py-3 rounded-xl hover:bg-emerald-50 transition font-semibold">
                        <i class="fas fa-home"></i> View Properties in {{ $project->city }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Similar Projects -->
        @if(isset($similarProjects) && $similarProjects->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($similarProjects as $similar)
                <x-project-card :project="$similar" />
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection