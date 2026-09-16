@props(['project'])

<div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
    <a href="{{ route('projects.show', $project->slug) }}" class="block">
        <div class="relative h-48 overflow-hidden">
            <img src="{{ $project->cover_image }}" alt="{{ $project->name }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            
            <!-- Status Badge -->
            <div class="absolute top-3 right-3">
                @if($project->status == 'ongoing')
                <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                 Ongoing
                </span>
                @elseif($project->status == 'completed')
                <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                Completed
                </span>
                @else
                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                    📋 Off-Plan
                </span>
                @endif
            </div>
            
            <!-- Featured Badge -->
            @if($project->is_featured)
            <div class="absolute top-3 left-3">
                <span class="bg-amber-400 text-amber-900 px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                    Featured
                </span>
            </div>
            @endif
            
            <!-- Progress Bar (for ongoing projects) -->
            @if($project->status == 'ongoing')
            <div class="absolute bottom-0 left-0 right-0">
                <div class="bg-black/50 backdrop-blur-sm px-3 py-1.5">
                    <div class="flex justify-between text-white text-xs mb-0.5">
                        <span>Progress</span>
                        <span>{{ $project->progress_percentage }}%</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="p-5">
            <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1 group-hover:text-emerald-600 transition">
                {{ $project->name }}
            </h3>
            <p class="text-gray-600 text-sm flex items-center gap-1 mb-2">
                <i class="fas fa-map-marker-alt text-emerald-600"></i>
                {{ $project->location }}, {{ $project->city }}
            </p>
            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                {{ Str::limit($project->description, 100) }}
            </p>
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-xs text-gray-500">Starting from</span>
                    <p class="text-emerald-600 font-bold">{{ $project->formatted_starting_price }}</p>
                </div>
                <span class="text-xs text-gray-400">{{ $project->developer }}</span>
            </div>
        </div>
    </a>
</div>
