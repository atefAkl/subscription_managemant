{{-- Enhanced Breadcrumb Component with FontAwesome --}}
@props(['items' => []])

@if(count($items) > 0)
<nav class="flex mb-2 custom-breadcrumb" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
        {{-- Home Icon --}}
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center font-medium text-gray-700 hover:text-blue-600 transition-colors">
                <i class="fas fa-home ml-2"></i>
                الرئيسية
            </a>
        </li>

        {{-- Breadcrumb Items --}}
        @foreach($items as $index => $item)
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-left text-gray-400 mx-2 text-xs"></i>
                @if($loop->last)
                    <span class="font-medium text-gray-500">{{ $item['label'] ?? $item['title'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        {{ $item['label'] ?? $item['title'] }}
                    </a>
                @endif
            </div>
        </li>
        @endforeach
    </ol>
</nav>
@endif