@extends('layout.pemilik')

@section('title', 'Buildin Material')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Building Materials</h1>
    <div class="flex space-x-2">
        <form method="GET" action="{{ route('materials.index') }}" class="inline">
            <select name="sort" onchange="this.form.submit()" class="border rounded-md px-3 py-2">
                <option value="">Sort by: Featured</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High
                </option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low
                </option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
            </select>
        </form>

        <!-- Tombol trigger modal -->
        <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-1"></i> Add Product
        </button>

    </div>
</div>

@foreach ($materials as $material)

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Inventory Management</h2>
        <form action="{{ route('materials.index') }}" method="GET" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search materials..."
                class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:ring focus:ring-blue-200 focus:border-blue-400">
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                <i class="fas fa-search mr-1"></i> Search
            </button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                        ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                        Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        BM-{{ str_pad($loop->iteration, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $material->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ $material->stock }}
                            in stock</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp{{ number_format($material->price, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($material->status)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Active</span>
                        @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-between items-center mt-4">
        <div class="text-sm text-gray-500">
            Showing {{ $materials->firstItem() }} to {{ $materials->lastItem() }} of {{ $materials->total() }} entries
        </div>

        <div class="flex space-x-1">
            @if ($materials->onFirstPage())
            <span class="px-3 py-1 border rounded-md text-sm text-gray-400">Previous</span>
            @else
            <a href="{{ $materials->previousPageUrl() }}" class="px-3 py-1 border rounded-md text-sm">Previous</a>
            @endif

            @for ($i = 1; $i <= $materials->lastPage(); $i++)
                <a href="{{ $materials->url($i) }}"
                    class="px-3 py-1 border rounded-md text-sm {{ $materials->currentPage() == $i ? 'bg-blue-600 text-white' : '' }}">
                    {{ $i }}
                </a>
                @endfor

                @if ($materials->hasMorePages())
                <a href="{{ $materials->nextPageUrl() }}" class="px-3 py-1 border rounded-md text-sm">Next</a>
                @else
                <span class="px-3 py-1 border rounded-md text-sm text-gray-400">Next</span>
                @endif
        </div>

    </div>
</div>

<!-- Product Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
    <!-- Product Card 1 -->
    <div
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition transform hover:-translate-y-1 duration-300 animate-fade-in delay-100">
        <div class="relative">
            <img src="{{ asset('storage/' . $material->path) }}" alt="Product" class="w-full h-48 object-cover">
            <div
                class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded animate-bounce">
                SALE
            </div>
        </div>

        <div class="p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-lg mb-1">{{ $material->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $material->description }}</p>
                </div>
                <div class="text-right">
                    <span
                        class="text-blue-600 font-bold block">Rp{{ number_format($material->price, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="mt-3 flex justify-between items-center">
                <div>
                    @if ($material->stock < 5) <span class="text-yellow-600 text-sm font-medium">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Low Stock ({{ $material->stock }})
                        </span>
                        @else
                        <span class="text-green-600 text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            In Stock ({{ $material->stock }})
                        </span>
                        @endif
                </div>
                <button class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                    <i class="fas fa-cart-plus mr-1"></i> Add
                </button>
            </div>
        </div>
    </div>

</div>
@endforeach
<!-- Modal Overlay -->
<div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <!-- Modal Box -->
    <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-lg relative">
        <h2 class="text-xl font-semibold mb-4">Add Product</h2>

        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block font-medium text-md text-gray-700">Name</label>
                <input type="text" name="name" id="name" required placeholder="Enter product name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="stock" class="block font-medium text-md text-gray-700">Stock</label>
                <input type="number" name="stock" id="stock" required placeholder="Enter stock quantity"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="price" class="block font-medium text-md text-gray-700">Price</label>
                <input type="number" name="price" id="price" step="0.01" required placeholder="Enter product price"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">

                <p class="text-sm text-gray-600 mt-1">Formatted: <span id="formattedPrice"
                        class="font-medium">Rp0</span></p>
            </div>
            <div>
                <label for="description" class="block font-medium text-md text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Enter product description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>


            <div>
                <label for="status" class="block font-medium text-md text-gray-700">Status</label>
                <select name="status" id="status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Choose status --</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div>
                <label for="image" class="block font-medium text-md text-gray-700">Image</label>
                <input type="file" name="image" id="image" accept="image/*" required
                    class="mt-1 block w-full text-lg text-gray-700 py-2 px-3 file:mr-4 file:py-3 file:px-4 file:rounded file:border-0 file:text-lg file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()"
                    class="px-5 py-3 bg-gray-200 rounded hover:bg-gray-300 text-md">Cancel</button>
                <button type="submit"
                    class="px-5 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 text-md">Save</button>
            </div>
        </form>

        <!-- Close Button -->
        <button onclick="closeModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('addProductModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('addProductModal').classList.add('hidden');
}
const priceInput = document.getElementById('price');
const formattedPrice = document.getElementById('formattedPrice');

priceInput.addEventListener('input', function() {
    const value = parseInt(this.value.replace(/\D/g, ''));
    if (!isNaN(value)) {
        formattedPrice.textContent = formatIDR(value);
    } else {
        formattedPrice.textContent = 'Rp0';
    }
});

function formatIDR(number) {
    return 'Rp' + number.toLocaleString('id-ID');
}
</script>

@endsection