@extends('layouts.app')

@section('title', 'Completed Property Projects')
@section('description', 'Explore our completed property development projects across Kenya.')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-sm font-semibold mb-3">
                 Completed Projects
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Completed Developments</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Explore our successfully completed property projects
            </p>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
            <x-project-card :project="$project" />
            @empty
            <div class="col-span-3 text-center py-16">
                <i class="fas fa-check-circle text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Completed Projects</h3>
                <p class="text-gray-500">Check back soon for completed developments.</p>
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
