@extends('layouts.app')

@section('title', 'Property Projects - Ongoing, Completed & Off-Plan')
@section('description', 'Explore our property projects including ongoing developments, completed projects, and off-plan investment opportunities.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Property Projects</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Discover our portfolio of residential and commercial projects across Kenya
            </p>
        </div>

        <!-- Quick Navigation -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="#ongoing" class="px-6 py-2 rounded-full bg-amber-500 text-white hover:bg-amber-600 transition font-medium">
                 Ongoing
            </a>
            <a href="#completed" class="px-6 py-2 rounded-full bg-emerald-500 text-white hover:bg-emerald-600 transition font-medium">
                 Completed
            </a>
            <a href="#off-plan" class="px-6 py-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition font-medium">
                 Off-Plan
            </a>
        </div>

        <!-- Ongoing Projects -->
        @if($ongoingProjects->count() > 0)
        <div id="ongoing" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                    Ongoing Projects
                </h2>
                <a href="{{ route('projects.ongoing') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ongoingProjects as $project)
                <x-project-card :project="$project" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Completed Projects -->
        @if($completedProjects->count() > 0)
        <div id="completed" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    Completed Projects
                </h2>
                <a href="{{ route('projects.completed') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($completedProjects as $project)
                <x-project-card :project="$project" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Off-Plan Projects -->
        @if($offPlanProjects->count() > 0)
        <div id="off-plan" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                    Off-Plan Projects
                </h2>
                <a href="{{ route('projects.off-plan') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offPlanProjects as $project)
                <x-project-card :project="$project" />
                @endforeach
            </div>
        </div>
        @endif

        @if($ongoingProjects->count() == 0 && $completedProjects->count() == 0 && $offPlanProjects->count() == 0)
        <div class="text-center py-16">
            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">No Projects Found</h3>
            <p class="text-gray-500">Check back soon for new property projects.</p>
        </div>
        @endif
    </div>
</div>
@endsection