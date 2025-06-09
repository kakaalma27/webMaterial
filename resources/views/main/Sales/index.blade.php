<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .animate-bounce-custom {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>

<body class="bg-blue-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-md">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="{{ route('karyawan.index') }}">
                    <div class="flex items-center w-14 h-14 space-x-2">
                        <img src="{{ asset('try.png') }}" class="w-15 h-15 text-2xl" alt="">
                    </div>
                </a>

                <div x-data="{ open: false }" class="relative">
                    <!-- Trigger -->
                    <div @click="open = !open" class="flex items-center space-x-2 cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <span
                            class="hidden md:inline text-sm font-medium text-gray-800">{{ auth()->user()->name }}</span>
                    </div>

                    <!-- Dropdown -->
                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-id-badge mr-2 text-blue-500"></i> Profil
                        </a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <form method="GET" action="{{ route('karyawan.index') }}">
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h2 class="text-xl font-semibold text-blue-700 mb-4">Filter Produk</h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Dropdown Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-blue-800 mb-1">Kategori</label>
                            <select id="category" name="category"
                                class="w-full border border-blue-200 bg-white text-blue-900 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Semua Kategori</option>
                                <option value="material" {{ request('category') == 'material' ? 'selected' : '' }}>
                                    Material
                                </option>
                                <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>
                                    Electrical</option>
                                <option value="paint" {{ request('category') == 'paint' ? 'selected' : '' }}>Paint
                                </option>
                                <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>
                                    Plumbing
                                </option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-blue-800 mb-1">Pencarian</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="w-full border border-blue-200 bg-white text-blue-900 rounded-xl px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <!-- Stock Status -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-blue-800 mb-1">Status Stok</label>
                            <select id="stock" name="stock"
                                class="w-full border border-blue-200 bg-white text-blue-900 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Semua</option>
                                <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>Tersedia</option>
                                <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Habis</option>
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md transition duration-300 ease-in-out">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="container mx-auto px-4 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-blue-800">Informasi Produk</h2>
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Tersedia</span>
                        </div>

                        <div class="grid grid-cols-1">
                            <div class="flex flex-col items-center justify-center mb-4">
                                <div
                                    class="w-48 h-48 md:w-56 md:h-56 bg-blue-50 rounded-xl flex items-center justify-center mb-4 overflow-hidden">
                                    <img src="{{ asset('storage/' . $product->path) }}" alt=""
                                        class="w-full h-full object-cover" />
                                </div>
                                <h3 class="text-lg font-semibold text-blue-800 text-center">{{ $product->name }}</h3>
                                <p class="text-gray-500">SKU: {{ $product->id }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                <p class="text-gray-600">{{ $product->description }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                    <p class="text-blue-600 font-bold">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                    <p class="text-gray-600 mb-2">{{ $product->stock }} </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <p class="text-gray-600">{{ ucfirst($product->type) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Beli Sekarang</label>

                                    <a href="{{ route('karyawan.show', ['type' => strtolower(class_basename($product)), 'id' => $product->id]) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors w-auto">
                                        <i class="fas fa-shopping-cart mr-1 text-xs"></i> Checkout
                                    </a>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center col-span-full">Tidak ada produk ditemukan.</p>
                @endforelse
            </div>
        </div>
        <footer class="bg-gradient-to-t from-blue-900 to-blue-700 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">BuildMaster</h3>
                        <p class="text-blue-200">Your one-stop shop for all construction and building materials.
                            Quality
                            products at competitive prices.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-blue-200 transition">Home</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Products</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Categories</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">About Us</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Categories</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-blue-200 transition">Tools</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Building Materials</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Electrical</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Plumbing</a></li>
                            <li><a href="#" class="hover:text-blue-200 transition">Paint & Decor</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Contact Us</h4>
                        <address class="not-italic">
                            <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> 123 Builder St, Construction
                                City</p>
                            <p class="mb-2"><i class="fas fa-phone mr-2"></i> (123) 456-7890</p>
                            <p class="mb-2"><i class="fas fa-envelope mr-2"></i> info@buildmaster.com</p>
                        </address>
                        <div class="mt-4 flex space-x-4">
                            <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white hover:text-blue-200"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-blue-700 mt-8 pt-6 text-center text-sm text-blue-200">
                    <p>&copy; 2023 BuildMaster. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <script>
        // Product price
        const productPrice = 79.99;
        let discount = 0;

        // Update totals
        function updateTotals() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const subtotal = productPrice * quantity;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        // Quantity controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value < 142) {
                input.value = value + 1;
                updateTotals();
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateTotals();
            }
        }

        // Apply coupon
        function applyCoupon() {
            const coupon = document.getElementById('coupon').value.trim().toUpperCase();
            const discountElement = document.getElementById('discount');

            if (coupon === 'SALE20') {
                const quantity = parseInt(document.getElementById('quantity').value);
                discount = productPrice * quantity * 0.2;
                discountElement.textContent = `-$${discount.toFixed(2)}`;
                discountElement.classList.add('text-green-600');
                updateTotals();

                // Show success message
                alert('Coupon applied successfully! 20% discount added.');
            } else if (coupon) {
                discount = 0;
                discountElement.textContent = '$0.00';
                discountElement.classList.remove('text-green-600');
                updateTotals();

                // Show error message
                alert('Invalid coupon code. Please try again.');
            }
        }

        // Process checkout
        function processCheckout() {
            const quantity = document.getElementById('quantity').value;
            const payment = document.getElementById('payment').value;
            const total = document.getElementById('total').textContent;

            alert(
                `Order confirmed!\n\nQuantity: ${quantity}\nPayment: ${payment}\nTotal: ${total}\n\nThank you for your purchase!`
            );

            // Reset form
            document.getElementById('quantity').value = 1;
            document.getElementById('payment').value = 'Credit Card';
            document.getElementById('coupon').value = '';
            discount = 0;
            document.getElementById('discount').textContent = '$0.00';
            updateTotals();
        }

        // Filter sales (placeholder)
        function filterSales() {
            alert('Filter options would appear here in a fully implemented version.');
        }

        // Initialize
        document.getElementById('quantity').addEventListener('change', updateTotals);
        document.getElementById('quantity').addEventListener('input', function() {
            if (this.value > 142) this.value = 142;
            if (this.value < 1) this.value = 1;
            updateTotals();
        });
        </script>
    </div>
</body>

</html>