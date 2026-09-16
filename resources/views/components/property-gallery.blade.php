@props(['property'])

<div class="property-gallery" x-data="{ activeImage: 0 }">
    <div class="relative">
        <!-- Main Image -->
        <div class="overflow-hidden rounded-lg bg-gray-100">
            <img 
                :src="images[activeImage]" 
                alt="{{ $property->title }}"
                class="h-96 w-full object-cover"
            >
        </div>
        
        <!-- Thumbnails -->
        @if($property->getMedia('property_images')->count() > 1)
            <div class="mt-4 grid grid-cols-5 gap-4">
                @foreach($property->getMedia('property_images') as $index => $image)
                    <button 
                        @click="activeImage = {{ $index }}"
                        class="relative rounded-md overflow-hidden"
                        :class="{ 'ring-2 ring-blue-500': activeImage === {{ $index }} }"
                    >
                        <img 
                            src="{{ $image->getUrl('thumb') }}" 
                            alt="Thumbnail {{ $index }}"
                            class="h-20 w-full object-cover"
                        >
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('propertyGallery', () => ({
            images: @json($property->getMedia('property_images')->map(fn($img) => $img->getUrl())),
        }))
    })
</script>
