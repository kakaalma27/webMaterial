@extends('layout.pemilik')

@section('title', 'ReStock Produk')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">ReStock Produk</h1>
    <div class="flex space-x-2">
        <!-- Sort and Type Filters Form -->
        <form method="GET" action="{{ route('restock.index') }}" class="inline-flex space-x-2 items-center">
            <!-- Hidden inputs to preserve other filters -->
            <input type="hidden" name="search" value="{{ request('search') }}">

            <!-- Product Type Filter -->
            <select name="type" onchange="this.form.submit()" class="border rounded-md px-3 py-2">
                <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Product Types</option>
                <option value="material" {{ request('type') == 'material' ? 'selected' : '' }}>Material</option>
                <option value="electrical" {{ request('type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                <option value="paint" {{ request('type') == 'paint' ? 'selected' : '' }}>Paint</option>
                <option value="plumbing" {{ request('type') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
            </select>

            <!-- Sort By Filter -->
            <select name="sort" onchange="this.form.submit()" class="border rounded-md px-3 py-2">
                <option value="">Sort by: Featured</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High
                </option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low
                </option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
            </select>
        </form>

        <!-- Add Product Button -->
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-1"></i> Add Product
        </button>

    </div>
</div>

<!-- Product Table Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Product Management</h2>
        <!-- Search Form -->
        <form action="{{ route('restock.index') }}" method="GET">
            <!-- Hidden inputs to preserve other filters -->
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto sm:overflow-x-visible">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                        ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                        Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type
                    </th>
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
                @forelse ($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ strtoupper(substr($product->product_type, 0, 2)) }}-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $product->product_type
                        }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ $product->stock }}
                            in stock</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($product->status)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Active</span>
                        @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="openEditModal('{{ $product->id }}', '{{ $product->product_type }}')"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('restock.destroy', $product->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <!-- Hidden input to pass product type for deletion -->
                            <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="flex justify-between items-center mt-4">
        <div class="text-sm text-gray-500">
            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
            entries
        </div>

        <div class="flex space-x-1">
            @if ($products->onFirstPage())
            <span class="px-3 py-1 border rounded-md text-sm text-gray-400">Previous</span>
            @else
            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1 border rounded-md text-sm">Previous</a>
            @endif

            @foreach ($products->links()->elements as $element)
            @if (is_string($element))
            <span class="px-3 py-1 border rounded-md text-sm text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
            @foreach ($element as $page => $url)
            <a href="{{ $url }}"
                class="px-3 py-1 border rounded-md text-sm {{ $products->currentPage() == $page ? 'bg-blue-600 text-white' : '' }}">
                {{ $page }}
            </a>
            @endforeach
            @endif
            @endforeach

            @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1 border rounded-md text-sm">Next</a>
            @else
            <span class="px-3 py-1 border rounded-md text-sm text-gray-400">Next</span>
            @endif
        </div>
    </div>
</div>

<!-- Product Grid Section (Optional - if you want to display products as cards too) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    @forelse ($products as $product)
    <div
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition transform hover:-translate-y-1 duration-300 animate-fade-in delay-100">
        <div class="relative">
            @if ($product->path)
            <img src="{{ asset('storage/' . $product->path) }}" alt="{{ $product->name }}"
                class="w-full h-48 object-cover">
            @else
            <img src="https://placehold.co/600x400/E0E0E0/333333?text=No+Image" alt="No Image"
                class="w-full h-48 object-cover">
            @endif
            @if ($product->stock < 5) <div
                class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded animate-bounce">
                LOW STOCK
        </div>
        @endif
    </div>

    <div class="p-4">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-bold text-lg mb-1">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm">{{ $product->description }}</p>
            </div>
            <div class="text-right">
                <span class="text-blue-600 font-bold block">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="mt-3 flex justify-between items-center">
            <div>
                @if ($product->stock < 5) <span class="text-yellow-600 text-sm font-medium">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    Low Stock ({{ $product->stock }})
                    </span>
                    @else
                    <span class="text-green-600 text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>
                        In Stock ({{ $product->stock }})
                    </span>
                    @endif
            </div>
            <button onclick="openEditModal('{{ $product->id }}', '{{ $product->product_type }}')"
                class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                <i class="fas fa-edit mr-1"></i> Edit
            </button>
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center text-gray-500 p-4">No products found in the grid view.</div>
@endforelse
</div>


<!-- Modal Overlay for Add Product -->
<div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-lg relative">
        <h2 class="text-xl font-semibold mb-4">Add Product</h2>

        <form action="{{ route('restock.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="add_product_type" class="block font-medium text-md text-gray-700">Product Type</label>
                <select name="product_type" id="add_product_type" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Choose Product Type --</option>
                    <option value="material">Material</option>
                    <option value="electrical">Electrical</option>
                    <option value="paint">Paint</option>
                    <option value="plumbing">Plumbing</option>
                </select>
            </div>

            <div>
                <label for="add_name" class="block font-medium text-md text-gray-700">Name</label>
                <input type="text" name="name" id="add_name" required placeholder="Enter product name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="add_stock" class="block font-medium text-md text-gray-700">Stock</label>
                <input type="number" name="stock" id="add_stock" required placeholder="Enter stock quantity"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="add_price" class="block font-medium text-md text-gray-700">Price</label>
                <input type="number" name="price" id="add_price" step="0.01" required placeholder="Enter product price"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Formatted: <span id="add_formattedPrice"
                        class="font-medium">Rp0</span></p>
            </div>
            <div>
                <label for="add_description" class="block font-medium text-md text-gray-700">Description</label>
                <textarea name="description" id="add_description" rows="3" placeholder="Enter product description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label for="add_status" class="block font-medium text-md text-gray-700">Status</label>
                <select name="status" id="add_status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Choose status --</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div>
                <label for="add_image" class="block font-medium text-md text-gray-700">Image</label>
                <input type="file" name="image" id="add_image" accept="image/*"
                    class="mt-1 block w-full text-lg text-gray-700 py-2 px-3 file:mr-4 file:py-3 file:px-4 file:rounded file:border-0 file:text-lg file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeAddModal()"
                    class="px-5 py-3 bg-gray-200 rounded hover:bg-gray-300 text-md">Cancel</button>
                <button type="submit"
                    class="px-5 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 text-md">Save</button>
            </div>
        </form>

        <button onclick="closeAddModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
    </div>
</div>

<!-- Modal Overlay for Edit Product (Single, Dynamic Modal) -->
<div id="editProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-lg relative">
        <h2 class="text-xl font-semibold mb-4">Edit Product</h2>

        <form id="editProductForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <!-- Hidden input for product type, crucial for update route -->
            <input type="hidden" name="product_type" id="edit_product_type_hidden">

            <div>
                <label for="edit_name" class="block font-medium text-md text-gray-700">Name</label>
                <input type="text" name="name" id="edit_name" required placeholder="Enter product name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="edit_stock" class="block font-medium text-md text-gray-700">Stock</label>
                <input type="number" name="stock" id="edit_stock" required placeholder="Enter stock quantity"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="edit_price" class="block font-medium text-md text-gray-700">Price</label>
                <input type="number" name="price" id="edit_price" step="0.01" required placeholder="Enter product price"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Formatted: <span id="edit_formattedPrice"
                        class="font-medium">Rp0</span></p>
            </div>
            <div>
                <label for="edit_description" class="block font-medium text-md text-gray-700">Description</label>
                <textarea name="description" id="edit_description" rows="3" placeholder="Enter product description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label for="edit_status" class="block font-medium text-md text-gray-700">Status</label>
                <select name="status" id="edit_status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm py-2 px-3 text-lg focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Choose status --</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div>
                <label for="edit_image" class="block font-medium text-md text-gray-700">Image</label>
                <input type="file" name="image" id="edit_image" accept="image/*"
                    class="mt-1 block w-full text-lg text-gray-700 py-2 px-3 file:mr-4 file:py-3 file:px-4 file:rounded file:border-0 file:text-lg file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-sm text-gray-600 mt-1" id="current_image_path"></p>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeEditModal()"
                    class="px-5 py-3 bg-gray-200 rounded hover:bg-gray-300 text-md">Cancel</button>
                <button type="submit"
                    class="px-5 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 text-md">Save</button>
            </div>
        </form>

        <button onclick="closeEditModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
    </div>
</div>


<script>
// Function to format number to Indonesian Rupiah
function formatIDR(number) {
    return 'Rp' + number.toLocaleString('id-ID');
}

// --- Add Product Modal Functions ---
function openAddModal() {
    document.getElementById('addProductModal').classList.remove('hidden');
    // Reset form fields when opening
    document.getElementById('add_product_type').value = '';
    document.getElementById('add_name').value = '';
    document.getElementById('add_stock').value = '';
    document.getElementById('add_price').value = '';
    document.getElementById('add_description').value = '';
    document.getElementById('add_status').value = '';
    document.getElementById('add_image').value = ''; // Clear file input
    document.getElementById('add_formattedPrice').textContent = 'Rp0';
}

function closeAddModal() {
    document.getElementById('addProductModal').classList.add('hidden');
}

// Event listener for Add Product price input formatting
const addPriceInput = document.getElementById('add_price');
const addFormattedPrice = document.getElementById('add_formattedPrice');

if (addPriceInput) {
    addPriceInput.addEventListener('input', function() {
        const value = parseInt(this.value.replace(/\D/g, ''));
        if (!isNaN(value)) {
            addFormattedPrice.textContent = formatIDR(value);
        } else {
            addFormattedPrice.textContent = 'Rp0';
        }
    });
}

// --- Edit Product Modal Functions ---
function openEditModal(id, type) {
    const editProductModal = document.getElementById('editProductModal');
    const editProductForm = document.getElementById('editProductForm');

    // Show loading indicator or clear previous data
    editProductForm.reset();
    document.getElementById('edit_formattedPrice').textContent = 'Rp0';
    document.getElementById('current_image_path').textContent = '';
    document.getElementById('edit_image').value = ''; // Clear file input

    // Set the form action dynamically
    editProductForm.action = `/restock/${id}`; // Laravel will handle PUT method
    document.getElementById('edit_product_type_hidden').value = type; // Set hidden product type

    // Fetch product data via AJAX
    fetch(`/admin/restock/product-details/${type}/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(product => {
            // Populate form fields with fetched data
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_status').value = product.status;

            // Format price for display
            document.getElementById('edit_formattedPrice').textContent = formatIDR(product.price);

            // Display current image path if available
            if (product.path) {
                document.getElementById('current_image_path').textContent = 'Current Image: ' + product.path.split(
                    '/').pop();
            } else {
                document.getElementById('current_image_path').textContent = 'No current image.';
            }

            editProductModal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            alert('Failed to load product details. Please try again.'); // Use a custom modal in production
            closeEditModal();
        });
}

function closeEditModal() {
    document.getElementById('editProductModal').classList.add('hidden');
}

// Event listener for Edit Product price input formatting
const editPriceInput = document.getElementById('edit_price');
const editFormattedPrice = document.getElementById('edit_formattedPrice');

if (editPriceInput) {
    editPriceInput.addEventListener('input', function() {
        const value = parseInt(this.value.replace(/\D/g, ''));
        if (!isNaN(value)) {
            editFormattedPrice.textContent = formatIDR(value);
        } else {
            editFormattedPrice.textContent = 'Rp0';
        }
    });

    // Format price on page load for the edit modal (if it was visible initially, though it's hidden now)
    // This part is more relevant if the modal was pre-populated for a specific product without AJAX.
    // For dynamic AJAX, the formatting happens after data fetch.
    const initialEditValue = parseInt(editPriceInput.value.replace(/\D/g, ''));
    editFormattedPrice.textContent = !isNaN(initialEditValue) ? formatIDR(initialEditValue) : 'Rp0';
}
</script>
@endsection