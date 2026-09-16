@extends('layouts.app')

@section('title', 'Off-Plan Property Projects')
@section('description', 'Explore our off-plan property investment opportunities across Kenya.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-semibold mb-3">
                📋 Off-Plan Projects
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Off-Plan Investment Opportunities</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Discover future developments and investment opportunities
            </p>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
            <x-project-card :project="$project" />
            @empty
            <div class="col-span-3 text-center py-16">
                <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Off-Plan Projects</h3>
                <p class="text-gray-500">Check back soon for new investment opportunities.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
