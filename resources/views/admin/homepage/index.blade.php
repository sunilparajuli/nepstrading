@extends('layouts.admin')

@section('title', 'Home Layout Manager')

@section('content')
<div class="px-8 py-6 max-w-5xl mx-auto">
    <!-- Add New Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Add New Homepage Section</h3>
        <form action="{{ route('admin.homepage.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="hero">Hero Banner</option>
                    <option value="featured_products">Featured Products</option>
                    <option value="popular_products">Popular Products</option>
                    <option value="new_products">New Products</option>
                    <option value="categories">Categories Grid</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Admin Title (Internal)</label>
                <input type="text" name="title" placeholder="e.g. Summer Hero, Best Sellers..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm">
                    + Add Section
                </button>
            </div>
        </form>
    </div>

    <!-- Active Sections -->
    <div id="section-list" class="space-y-6">
        @forelse($sections as $section)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden section-item" data-id="{{ $section->id }}">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center cursor-move handle">
                <div class="flex items-center space-x-3">
                    <span class="text-gray-400 font-bold opacity-50">SHAPE</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase">{{ str_replace('_', ' ', $section->type) }}</span>
                    <span class="font-bold text-gray-700">{{ $section->title ?: 'Untitled Section' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <form action="{{ route('admin.homepage.update', $section) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $section->is_active ? 0 : 1 }}">
                        <button type="submit" class="p-1 px-3 text-xs font-bold rounded {{ $section->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $section->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.homepage.destroy', $section) }}" method="POST" onsubmit="return confirm('Remove this section?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1 text-red-500 hover:bg-red-50 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.homepage.update', $section) }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Public Display Title</label>
                            <input type="text" name="title" value="{{ $section->title }}" class="w-full px-3 py-2 border border-blue-100 rounded-lg text-sm bg-blue-50/30 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Subtitle / Description</label>
                            <input type="text" name="subtitle" value="{{ $section->subtitle }}" class="w-full px-3 py-2 border border-blue-100 rounded-lg text-sm bg-blue-50/30">
                        </div>
                    </div>

                    @if($section->type === 'hero')
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 text-blue-600">Hero Configuration</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Button Text</label>
                                    <input type="text" name="data[button_text]" value="{{ $section->data['button_text'] ?? 'Shop Now' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Button Link</label>
                                    <input type="text" name="data[button_link]" value="{{ $section->data['button_link'] ?? '/products' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-gray-400 mb-1">Background Image URL</label>
                                    <input type="text" name="data[bg_image]" value="{{ $section->data['bg_image'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    @elseif(in_array($section->type, ['featured_products', 'popular_products', 'new_products', 'categories']))
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-xs font-bold text-gray-500 uppercase text-blue-600">Hand-picked Products</h4>
                                <span class="text-[10px] text-gray-400 font-bold italic">Leave empty to use automatic selection (Seasonal, Popular, etc.)</span>
                            </div>
                            
                            <div class="mb-4">
                                <select name="data[product_ids][]" multiple class="product-selector w-full" style="height: 150px">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, $section->data['product_ids'] ?? []) ? 'selected' : '' }}>
                                            {{ $product->name }} (${{ number_format($product->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500 block mb-1">Limit (if auto)</label>
                                    <input type="number" name="data[limit]" value="{{ $section->data['limit'] ?? 4 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block mb-1">Layout Columns</label>
                                    <select name="data[columns]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="3" {{ ($section->data['columns'] ?? '') == 3 ? 'selected' : '' }}>3 Columns</option>
                                        <option value="4" {{ ($section->data['columns'] ?? '') == 4 ? 'selected' : '' }}>4 Columns</option>
                                        <option value="6" {{ ($section->data['columns'] ?? '') == 6 ? 'selected' : '' }}>6 Columns</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-gray-800 hover:bg-black text-white font-black py-2 px-8 rounded-lg transition-colors text-sm uppercase tracking-wide">
                            Save Section Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <span class="text-4xl mb-4 block">🏠</span>
            <h3 class="text-lg font-bold text-gray-400">No homepage sections yet</h3>
            <p class="text-gray-400 text-sm">Use the form above to start building your homepage</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container--default .select2-selection--multiple {
        border-radius: 0.5rem;
        border-color: rgb(209 213 219);
        padding: 4px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: rgb(59 130 246);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #f3f4f6;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.product-selector').select2({
            placeholder: "Search and pick products...",
            allowClear: true
        });

        // Initialize Sortable
        const el = document.getElementById('section-list');
        Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const ids = [];
                $('#section-list .section-item').each(function() {
                    ids.push($(this).data('id'));
                });

                $.ajax({
                    url: "{{ route('admin.homepage.reorder') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },
                    success: function() {
                        // Success notification could go here
                        console.log('Reorder successful');
                    }
                });
            }
        });
    });
</script>
@endsection
