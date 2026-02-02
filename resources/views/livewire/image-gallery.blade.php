<div class="relative">
    <!-- Main Image Display -->
    <div class="relative group">
        @php
            $mainImagePath = '';
            if($mainImage) {
                if(file_exists(public_path($mainImage))) {
                    $mainImagePath = asset($mainImage);
                } else {
                    $mainImagePath = asset('storage/'.$mainImage);
                }
            } else {
                $mainImagePath = asset('images/default-product.png');
            }
        @endphp
        <img 
            src="{{ $mainImagePath }}" 
            alt="{{ $product->productName ?? 'Product' }}" 
            class="w-full h-96 object-contain rounded-lg cursor-zoom-in"
            wire:click="zoomImage('{{ $mainImage }}')"
        >
        
        <!-- Zoom hint with clickable button -->
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity flex items-center justify-center opacity-0 group-hover:opacity-100">
            <button 
                wire:click="zoomImage('{{ $mainImage }}')"
                class="text-white bg-purple-700 hover:bg-purple-800 px-4 py-2 rounded-full text-lg font-bold transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                aria-label="Click to zoom image"
            >
                Click to Zoom
            </button>
        </div>
    </div>
    
    <!-- Thumbnail Gallery -->
    <div class="flex space-x-2 mt-4 overflow-x-auto py-2">
        @foreach($galleryImages as $image)
            @php
                $thumbnailPath = '';
                if($image) {
                    if(file_exists(public_path($image))) {
                        $thumbnailPath = asset($image);
                    } else {
                        $thumbnailPath = asset('storage/'.$image);
                    }
                } else {
                    $thumbnailPath = asset('images/default-product.png');
                }
            @endphp
            <img 
                src="{{ $thumbnailPath }}" 
                alt="Thumbnail" 
                class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 {{ $mainImage === $image ? 'border-purple-700' : 'border-gray-300' }}"
                wire:click="selectImage('{{ $image }}')"
            >
        @endforeach
    </div>
    
    <!-- Zoom Modal -->
    @if($showZoom)
        <div 
            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
            style="display: flex;"
        >
            <div class="relative max-w-4xl max-h-full p-4">
                @php
                    $zoomedImagePath = '';
                    if($zoomedImage) {
                        if(file_exists(public_path($zoomedImage))) {
                            $zoomedImagePath = asset($zoomedImage);
                        } else {
                            $zoomedImagePath = asset('storage/'.$zoomedImage);
                        }
                    } else {
                        $zoomedImagePath = asset('images/default-product.png');
                    }
                @endphp
                <img 
                    src="{{ $zoomedImagePath }}" 
                    alt="Zoomed Image" 
                    class="max-w-full max-h-full object-contain"
                >
                
                <!-- Close Button -->
                <button 
                    class="absolute top-4 right-4 text-white bg-red-600 rounded-full w-10 h-10 flex items-center justify-center text-xl font-bold hover:bg-red-700"
                    wire:click="closeZoom()"
                >
                    ×
                </button>
                
                <!-- Close on Click Outside -->
                <div 
                    class="absolute inset-0"
                    wire:click="closeZoom()"
                ></div>
            </div>
        </div>
    @endif
</div>
