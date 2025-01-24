<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ isOpen: false, currentImage: null, images: @js($getRecord()->images->pluck('url')->toArray()), currentIndex: 0 }">
        <!-- Image Grid -->
        <div class="grid grid-cols-1 gap-4">
            @foreach($getRecord()->images as $index => $image)
                <div class="relative group cursor-pointer" @click="isOpen = true; currentIndex = {{ $index }}; currentImage = images[{{ $index }}]">
                    <img 
                        src="{{ $image->url }}" 
                        alt="Report Image {{ $index + 1 }}" 
                        class="w-full h-32 object-cover rounded-lg transition-all duration-300 group-hover:brightness-90"
                    >
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-black bg-opacity-50 text-white px-3 py-1 rounded">
                            Vergrößern
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Overlay -->
        <div x-show="isOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 cursor-pointer"
             @keydown.escape.window="isOpen = false"
             @click="isOpen = false">
            
            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div 
                    @click.stop
                    class="bg-white rounded-lg p-4 shadow-xl cursor-default"
                    style="max-width: 50vw; max-height: 50vh;"
                >
                    <!-- Image container -->
                    <div class="relative h-full">
                        <img 
                            :src="currentImage" 
                            alt="Report Image" 
                            class="max-w-full max-h-[calc(50vh-2rem)] object-contain mx-auto"
                        >
                        
                        <!-- Navigation -->
                        <div class="flex justify-between items-center mt-4">
                            <button 
                                @click.stop="currentIndex = (currentIndex - 1 + images.length) % images.length; currentImage = images[currentIndex]"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 p-2 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>

                            <div class="text-gray-600">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </div>

                            <button 
                                @click.stop="currentIndex = (currentIndex + 1) % images.length; currentImage = images[currentIndex]"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 p-2 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
