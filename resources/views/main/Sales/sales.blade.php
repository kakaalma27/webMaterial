<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
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

        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


                <!-- Left Column - Product and Buy Section -->
                <div class="lg:col-span-2">
                    <!-- Product Item Section -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 card-hover">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-blue-800">Informasi Produk</h2>
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Tersedia</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex flex-col items-center justify-center">
                                    <img src="{{ asset('storage/' . $product->path) }}" alt=""
                                        class="w-48 h-48 bg-blue-50 rounded-lg flex items-center justify-center mb-4 animate-bounce-custom object-cover" />
                                    <h3 class="text-lg font-semibold text-blue-800">{{ $product->name }}</h3>
                                    <p class="text-gray-500">SKU: {{ $product->id }}</p>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                        <p class="text-gray-600">{{ $product->description }}
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                            <p class="text-blue-600 font-bold">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                            <p class="text-gray-600">{{ $product->stock }} </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                            <p class="text-gray-600">{{ ucfirst($type) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buy Item Section -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                        <!-- Notifikasi -->
                        @if(session('success') || session('error'))
                        <div id="notification" class="fixed top-5 right-5 max-w-xs w-full px-4 py-3 rounded shadow-lg text-white
    {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}
    flex items-center space-x-3 z-50" role="alert">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                @if(session('success'))
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                @endif
                            </svg>
                            <span class="flex-1 text-sm font-semibold">
                                {{ session('success') ?? session('error') }}
                            </span>
                            <button onclick="document.getElementById('notification').remove()"
                                class="focus:outline-none" aria-label="Close notification">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <script>
                        setTimeout(() => {
                            const notif = document.getElementById('notification');
                            if (notif) notif.remove();
                        }, 5000); // Hilang setelah 5000 ms (5 detik)
                        </script>
                        @endif

                        <div class="p-6">
                            <h2 class="text-xl font-bold text-blue-800 mb-6">Purchase Product</h2>

                            <form method="POST" action="{{ route('karyawan.store') }}">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <label for="quantity"
                                            class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                        <div class="flex rounded-md shadow-sm">
                                            <button type="button" onclick="decreaseQuantity()"
                                                class="px-4 py-2 border border-r-0 border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-l-md">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                                max="142"
                                                class="w-full px-4 py-2 border-t border-b border-gray-300 text-center focus:ring-blue-500 focus:border-blue-500">
                                            <button type="button" onclick="increaseQuantity()"
                                                class="px-4 py-2 border border-l-0 border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-r-md">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="payment"
                                            class="block text-sm font-medium text-gray-700 mb-1">Payment
                                            Method</label>
                                        <select name="payment_id" id="payment"
                                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @foreach ($payment as $payments)
                                            <option value="{{ $payments->id }}">{{ $payments->name }} -
                                                {{ $payments->number }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="bg-blue-50 p-4 rounded-lg">
                                            <div class="flex justify-between mb-2">
                                                <span class="text-gray-600">Subtotal:</span>
                                                <span class="font-medium" id="subtotal">
                                                    Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                            </div>
                                            <input type="hidden" name="price" id="priceInput"
                                                value="{{ $product->price }}">

                                            <div class="flex justify-between text-lg font-bold">
                                                <span class="text-gray-700">Total:</span>
                                                <span class="text-blue-600" id="total">
                                                    Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="flex space-x-4">
                                            <button type="submit"
                                                class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                <i class="fas fa-shopping-cart mr-2"></i> Checkout
                                            </button>
                                            <button type="button"
                                                onclick="window.location.href='{{ route('karyawan.index') }}'"
                                                class="flex-1 px-6 py-3 border border-red-600 text-red-600 font-medium rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                <i class="fas fa-times mr-2"></i> Batal
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - History Sales -->
                <div>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden h-full card-hover">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-blue-800">Sales History</h2>
                                <button onclick="toggleDropdown()"
                                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200">
                                    <i class="fas fa-filter mr-1"></i> Filter
                                </button>

                                <div id="dropdown"
                                    class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50">

                                    <!-- Link Semua Pembayaran -->

                                    @foreach ($payment as $pay)
                                    <a href="{{ route('sales.filter', ['type' => $type, 'id' => $product->id, 'payment_id' => $pay->id]) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                        {{ $pay->name }}
                                    </a>
                                    @endforeach
                                    <a href="{{ route('karyawan.show', ['type' => $type, 'id' => $product->id]) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                        Semua
                                    </a>
                                </div>

                            </div>

                            <div class="mb-6 bg-blue-50 p-3 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Total Sales</h3>
                                        <p class="text-xl font-bold text-blue-600">
                                            Rp{{ number_format($totalSales, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>


                            <div class="max-h-100 overflow-y-auto scrollbar-hide">
                                <div class="space-y-4">
                                    @forelse ($salesHistory as $sale)
                                    <div
                                        class="flex items-start p-3 border-b border-gray-100 hover:bg-blue-50 rounded-lg transition-colors">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                            <i class="fas fa-receipt text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h4 class="font-medium text-gray-800">Order {{ $sale->id }}</h4>
                                                <span class="text-sm font-bold text-blue-600">
                                                    Rp{{ number_format($sale->price , 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $sale->quantity }}x {{ $product->name }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $sale->created_at->diffForHumans() }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-credit-card mr-1"></i>
                                                {{ $sale->payment ? $sale->payment->name : 'No payment method' }}
                                            </p>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-gray-500 text-center">No sales history yet.</p>
                                    @endforelse
                                </div>
                                <div class="mt-4">
                                    {{ $salesHistory->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        const productPrice = <?= $product->price ?>;
        let discount = 0;

        // Fungsi format Rupiah
        function formatRupiah(angka) {
            return 'Rp' + angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Update totals
        function updateTotals() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const subtotal = productPrice * quantity;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = formatRupiah(subtotal);
            document.getElementById('total').textContent = formatRupiah(total);

            // Update hidden price input
            document.getElementById('priceInput').value = subtotal;
        }


        // Tombol tambah jumlah
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value < 142) {
                input.value = value + 1;
                updateTotals();
            }
        }

        // Tombol kurang jumlah
        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateTotals();
            }
        }

        // Proses checkout
        function processCheckout() {
            const quantity = document.getElementById('quantity').value;
            const payment = document.getElementById('payment').value;
            const total = document.getElementById('total').textContent;

            alert(
                `Pesanan dikonfirmasi!\n\nJumlah: ${quantity}\nPembayaran: ${payment}\nTotal: ${total}\n\nTerima kasih atas pembelian Anda!`
            );

            // Reset form
            document.getElementById('quantity').value = 1;
            document.getElementById('payment').value = 'Credit Card';
            document.getElementById('coupon').value = '';
            discount = 0;
            document.getElementById('discount').textContent = formatRupiah(0);
            updateTotals();
        }

        // Filter sales (placeholder)
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Inisialisasi event listener
        document.getElementById('quantity').addEventListener('change', updateTotals);
        document.getElementById('quantity').addEventListener('input', function() {
            if (this.value > 142) this.value = 142;
            if (this.value < 1) this.value = 1;
            updateTotals();
        });
        </script>

</body>

</html>