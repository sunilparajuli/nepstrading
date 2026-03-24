@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Column -->
        <div class="w-full lg:w-3/4 space-y-8">
            <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                            class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" required 
                            class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="10" 
                            class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Short Description</label>
                        <textarea name="short_description" rows="4" 
                            class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Product Data (Tabs) -->
            <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex border-b border-gray-100 bg-gray-50">
                    <button type="button" class="px-6 py-3 text-sm font-bold border-r border-gray-100 bg-white text-primary">General</button>
                    <button type="button" class="px-6 py-3 text-sm font-bold border-r border-gray-100 text-gray-500 hover:text-gray-800">Inventory</button>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Pricing Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Regular Price ($)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required 
                                class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Sale Price ($)</label>
                            <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" 
                                class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                        </div>
                    </div>

                    <!-- Inventory Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-gray-50">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" 
                                class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Stock Status</label>
                            <select name="stock_status" class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                                <option value="instock" {{ $product->stock_status == 'instock' ? 'selected' : '' }}>In Stock</option>
                                <option value="outofstock" {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 flex items-center space-x-4">
                            <input type="checkbox" name="manage_stock" id="manage_stock" value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="manage_stock" class="text-sm font-medium text-gray-700">Manage stock?</label>
                        </div>
                        <div id="stock_qty_container" class="{{ old('manage_stock', $product->manage_stock) ? '' : 'hidden' }}">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50">
                        <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-6">Flash Sale Settings</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Deal Ends At</label>
                                <input type="datetime-local" name="deal_ends_at" value="{{ old('deal_ends_at', $product->deal_ends_at ? $product->deal_ends_at->format('Y-m-d\TH:i') : '') }}" 
                                    class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Deal Total Stock</label>
                                <input type="number" name="deal_total_stock" value="{{ old('deal_total_stock', $product->deal_total_stock) }}" 
                                    class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <!-- Cart Control Section -->
                    <div class="pt-8 border-t border-gray-50">
                        <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-6">Cart & Purchase Control</h4>
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <input type="checkbox" name="allow_add_to_cart" id="allow_add_to_cart" value="1" {{ old('allow_add_to_cart', $product->allow_add_to_cart) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="allow_add_to_cart" class="text-sm font-medium text-gray-700">Allow customers to add this product to cart?</label>
                            </div>
                            <div id="cart_disabled_message_container" class="{{ old('allow_add_to_cart', $product->allow_add_to_cart) ? 'hidden' : '' }}">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Message when disabled (Details)</label>
                                <textarea name="cart_disabled_message" rows="3" placeholder="e.g. Please visit our physical shop to purchase this item."
                                    class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">{{ old('cart_disabled_message', $product->cart_disabled_message) }}</textarea>
                                <p class="text-[10px] text-gray-400 mt-1 italic">This message will be shown instead of the 'Add to Cart' button.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="w-full lg:w-1/4 space-y-8">
            <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
                <h3 class="font-bold mb-4 uppercase text-xs tracking-wider text-gray-400 border-b border-gray-50 pb-2">Actions</h3>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-sm py-3 text-sm font-bold hover:bg-blue-700 transition-colors shadow-md mb-3">Update Product</button>
                <button type="button" onclick="if(confirm('Are you sure?')) document.getElementById('delete-product-form-{{ $product->id }}').submit();" class="w-full text-red-500 text-xs hover:underline text-center">Delete Product</button>
            </div>

            <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
                <h3 class="font-bold mb-4 uppercase text-xs tracking-wider text-gray-400 border-b border-gray-50 pb-2">Product Categories</h3>
                <div class="max-h-48 overflow-y-auto space-y-2 mb-4">
                    @php $productCategoryIds = $product->categories->pluck('id')->toArray(); @endphp
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-3 text-sm">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                {{ in_array($category->id, $productCategoryIds) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
                <h3 class="font-bold mb-4 uppercase text-xs tracking-wider text-gray-400 border-b border-gray-50 pb-2">Product Attributes</h3>
                <div class="space-y-6 max-h-96 overflow-y-auto pr-2">
                    @php $productAttributeTermIds = $product->attributes->pluck('id')->toArray(); @endphp
                    @foreach($attributes as $attribute)
                        <div class="space-y-2">
                            <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest">{{ $attribute->name }}</h4>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($attribute->terms as $term)
                                    <label class="flex items-center space-x-2 text-xs cursor-pointer group">
                                        <input type="checkbox" name="attributes[]" value="{{ $term->id }}"
                                            {{ in_array($term->id, $productAttributeTermIds) ? 'checked' : '' }}
                                            class="w-3 h-3 text-primary focus:ring-primary border-gray-300 rounded-sm">
                                        <span class="group-hover:text-primary transition-colors">{{ $term->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
                <h3 class="font-bold mb-4 uppercase text-xs tracking-wider text-gray-400 border-b border-gray-50 pb-2">Product Image</h3>
                @if($product->image)
                    <div class="mb-4">
                        <img src="{{ $product->image }}" class="w-full h-auto rounded-sm border border-gray-100">
                    </div>
                @endif
                <div class="border-2 border-dashed border-gray-200 rounded-sm p-4 text-center">
                    <input type="file" name="image" class="text-xs">
                </div>
            </div>
        </div>
    </div>
</form>

<form id="delete-product-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    document.getElementById('manage_stock').addEventListener('change', function() {
        document.getElementById('stock_qty_container').classList.toggle('hidden', !this.checked);
    });
    document.getElementById('allow_add_to_cart').addEventListener('change', function() {
        document.getElementById('cart_disabled_message_container').classList.toggle('hidden', this.checked);
    });
</script>
@endsection
