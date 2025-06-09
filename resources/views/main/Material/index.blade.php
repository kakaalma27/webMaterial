@extends('layout.pemilik')

@section('title', 'Buildin Materials')

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



<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Materials Management</h2>
        <form action="{{ route('materials.index') }}" method="GET">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto sm:overflow-x-visible">
        <table class="w-full divide-y divide-gray-200">
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
                @foreach ($Materials as $Material)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        BM-{{ str_pad($loop->iteration, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $Material->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ $Material->stock }}
                            in stock</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp{{ number_format($Material->price, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($Material->status)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Active</span>
                        @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="openModalEdit('{{ $Material->id }}')"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('materials.destroy', $Material->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>
                <div id="editProductModal-{{ $Material->id }}"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <!-- Modal Box -->
                    <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-lg relative">
                        <h2 class="text-xl font-semibold mb-4">Edit Product</h2>

                        <form action="{{ route('materials.update', $Material->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="name" class="block font-medium text-md text-gray-700">Name</label>
                                <input type="text" name="name" value="{{ $Material->name }}" id="name" required
                                    placeholder="Enter product name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="stock" class="block font-medium text-md text-gray-700">Stock</label>
                                <input type="number" name="stock" value="{{ $Material->stock }}" id="stock" required
                                    placeholder="Enter stock quantity"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="price" class="block font-medium text-md text-gray-700">Price</label>
                                <input type="number" name="price" id="editPrice" value="{{ $Material->price }}"
                                    step="0.01" required placeholder="Enter product price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">

                                <p class="text-sm text-gray-600 mt-1">Formatted: <span id="editFormattedPrice"
                                        class="font-medium">Rp0</span></p>
                            </div>
                            <div>
                                <label for="description"
                                    class="block font-medium text-md text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    placeholder="Enter product description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">{{ $Material->description }}</textarea>
                            </div>
                            <div>
                                <label for="status" class="block font-medium text-md text-gray-700">Status</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Choose status --</option>
                                    <option value="1" {{ $Material->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $Material->status == 0 ? 'selected' : '' }}>Inactive</option>

                                </select>
                            </div>

                            <div>
                                <label for="image" class="block font-medium text-md text-gray-700">Image</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="mt-1 block w-full text-lg text-gray-700 py-2 px-3 file:mr-4 file:py-3 file:px-4 file:rounded file:border-0 file:text-lg file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>

                            <div class="flex justify-end space-x-2 mt-4">
                                <button type="button" onclick="closeModalEdit('{{ $Material->id }}')"
                                    class="px-5 py-3 bg-gray-200 rounded hover:bg-gray-300 text-md">Cancel</button>
                                <button type="submit"
                                    class="px-5 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 text-md">Save</button>
                            </div>
                        </form>

                        <!-- Close Button -->
                        <button onclick="closeModalEdit('{{ $Material->id }}')"
                            class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-between items-center mt-4">
        <div class="text-sm text-gray-500">
            Showing {{ $Materials->firstItem() }} to {{ $Materials->lastItem() }} of {{ $Materials->total() }}
            entries
        </div>

        <div class="flex space-x-1">
            @if ($Materials->onFirstPage())
            <span class="px-3 py-1 border rounded-md text-sm text-gray-400">Previous</span>
            @else
            <a href="{{ $Materials->previousPageUrl() }}" class="px-3 py-1 border rounded-md text-sm">Previous</a>
            @endif

            @for ($i = 1; $i <= $Materials->lastPage(); $i++)
                <a href="{{ $Materials->url($i) }}"
                    class="px-3 py-1 border rounded-md text-sm {{ $Materials->currentPage() == $i ? 'bg-blue-600 text-white' : '' }}">
                    {{ $i }}
                </a>
                @endfor

                @if ($Materials->hasMorePages())
                <a href="{{ $Materials->nextPageUrl() }}" class="px-3 py-1 border rounded-md text-sm">Next</a>
                @else
                <span class="px-3 py-1 border rounded-md text-sm text-gray-400">Next</span>
                @endif
        </div>

    </div>
</div>

<!-- Product Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
    @foreach ($Materials as $Material)
    <div
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition transform hover:-translate-y-1 duration-300 animate-fade-in delay-100">
        <div class="relative">
            <img src="{{ asset('storage/' . $Material->path) }}" alt="Product" class="w-full h-48 object-cover">
            <div
                class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded animate-bounce">
                SALE
            </div>
        </div>

        <div class="p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-lg mb-1">{{ $Material->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $Material->description }}</p>
                </div>
                <div class="text-right">
                    <span
                        class="text-blue-600 font-bold block">Rp{{ number_format($Material->price, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="mt-3 flex justify-between items-center">
                <div>
                    @if ($Material->stock < 5) <span class="text-yellow-600 text-sm font-medium">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Low Stock ({{ $Material->stock }})
                        </span>
                        @else
                        <span class="text-green-600 text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            In Stock ({{ $Material->stock }})
                        </span>
                        @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- Modal Overlay Edit -->


<!-- Modal Overlay Store -->
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

function openModalEdit(id) {
    document.getElementById('editProductModal-' + id).classList.remove('hidden');
}

function closeModalEdit(id) {
    document.getElementById('editProductModal-' + id).classList.add('hidden');
}

function formatIDR(number) {
    return 'Rp' + number.toLocaleString('id-ID');
}

// --- Modal Add ---
const priceInput = document.getElementById('price');
const formattedPrice = document.getElementById('formattedPrice');

if (priceInput) {
    priceInput.addEventListener('input', function() {
        const value = parseInt(this.value.replace(/\D/g, ''));
        if (!isNaN(value)) {
            formattedPrice.textContent = formatIDR(value);
        } else {
            formattedPrice.textContent = 'Rp0';
        }
    });
}

// --- Modal Edit ---
const editPriceInput = document.getElementById('editPrice');
const editFormattedPrice = document.getElementById('editFormattedPrice');

if (editPriceInput) {
    // Format saat halaman dimuat
    const currentValue = parseInt(editPriceInput.value.replace(/\D/g, ''));
    editFormattedPrice.textContent = !isNaN(currentValue) ? formatIDR(currentValue) : 'Rp0';

    editPriceInput.addEventListener('input', function() {
        const value = parseInt(this.value.replace(/\D/g, ''));
        if (!isNaN(value)) {
            editFormattedPrice.textContent = formatIDR(value);
        } else {
            editFormattedPrice.textContent = 'Rp0';
        }
    });
}
</script>


@endsection