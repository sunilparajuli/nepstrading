<a href="{{ route('categories.show', $category) }}" class="cat-modern-card group">
    <div class="cat-icon-wrap">
        {{-- Custom logic for icons based on category name --}}
        @php
            $icon = 'package';
            $name = strtolower($category->name);
            if (str_contains($name, 'pooja') || str_contains($name, 'decor')) $icon = 'sparkles';
            elseif (str_contains($name, 'spice') || str_contains($name, 'masala')) $icon = 'flame';
            elseif (str_contains($name, 'rice') || str_contains($name, 'grain')) $icon = 'wheat';
            elseif (str_contains($name, 'snack') || str_contains($name, 'food')) $icon = 'utensils';
            elseif (str_contains($name, 'beverage') || str_contains($name, 'drink')) $icon = 'coffee';
        @endphp
        
        @if($icon == 'sparkles')
            <svg class="text-blue-600 transition-colors group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
        @elseif($icon == 'flame')
            <svg class="text-blue-600 transition-colors group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
        @elseif($icon == 'wheat')
            <svg class="text-blue-600 transition-colors group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22 22 2"/><path d="M10 20c3.9 0 7-3.1 7-7 0-.7-.1-1.4-.3-2"/><path d="M14 10c-3.9 0-7 3.1-7 7 0 .7.1 1.4.3 2"/><path d="M18 14c2.2 0 4-1.8 4-4 0-.4-.1-.9-.2-1.3"/><path d="M10 6c-2.2 0-4 1.8-4 4 0 .4.1.9.2 1.3"/><path d="M18 6c1.7 0 3-1.3 3-3 0-.3-.1-.5-.2-.8"/><path d="M6 18c-1.7 0-3 1.3-3 3 0 .3.1.5.2.8"/></svg>
        @elseif($icon == 'utensils')
            <svg class="text-blue-600 transition-colors group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
        @else
            <svg class="text-blue-600 transition-colors group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
        @endif
    </div>
    <h3>{{ $category->name }}</h3>
    <p>{{ $category->products_count }} Products Available</p>
</a>
