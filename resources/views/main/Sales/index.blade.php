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
        <header class="bg-white shadow-md">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="{{ route('karyawan.index') }}">
                    <div class="flex items-center w-14 h-14 space-x-2">
                        <img src="{{ asset('try.png') }}" class="w-15 h-15 text-2xl" alt="">
                    </div>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('cart.show') }}" class="relative text-gray-600 hover:text-blue-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php
                        $cartCount = count(session()->get('cart', []));
                        @endphp
                        @if ($cartCount > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <div @click="open = !open" class="flex items-center space-x-2 cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                <img src="{{ auth()->user()->profile_photo_path 
        ? asset('storage/' . auth()->user()->profile_photo_path) 
        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                    class="w-10 h-10 rounded-full object-cover">

                            </div>
                            <span
                                class="hidden md:inline text-sm font-medium text-gray-800">{{ auth()->user()->name }}</span>
                        </div>

                        <div x-show="open" x-cloak @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <button onclick="openModal()"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-id-badge mr-2 text-blue-500"></i> Profil
                            </button>
                            <div id="updateModal"
                                class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
                                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md relative">
                                    <button onclick="closeModal()"
                                        class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-2xl font-semibold leading-none">&times;</button>
                                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Update Profil</h2>

                                    <form action="{{ route('user.update', Auth::user()->id) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-5">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label for="profile_photo_path"
                                                class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                                            <input type="file" name="profile_photo_path" id="profile_photo_path"
                                                accept="image/*"
                                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                            <div class="mt-4 flex justify-center items-center">
                                                <img id="profile_photo_preview" src="{{ auth()->user()->profile_photo_path 
        ? asset('storage/' . auth()->user()->profile_photo_path) 
        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" alt="Profile Preview"
                                                    class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
                                            </div>
                                        </div>

                                        <div>
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}"
                                                required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                        </div>

                                        <div>
                                            <label for="email"
                                                class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" name="email" id="email"
                                                value="{{ Auth::user()->email }}" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                        </div>

                                        <div>
                                            <label for="role"
                                                class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                            <select name="role" id="role"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                                disabled>
                                                <option value="user"
                                                    {{ Auth::user()->role == 'user' ? 'selected' : '' }}>
                                                    User
                                                </option>
                                            </select>
                                        </div>

                                        <div class="text-right mt-6">
                                            <button type="submit"
                                                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script>
                            function openModal() {
                                document.getElementById('updateModal').classList.remove('hidden');
                            }

                            function closeModal() {
                                document.getElementById('updateModal').classList.add('hidden');
                            }

                            // Optional: Close modal when clicking outside
                            window.addEventListener('click', function(e) {
                                const modal = document.getElementById('updateModal');
                                if (e.target === modal) closeModal();
                            });

                            // Real-time image preview
                            document.getElementById('profile_photo_path').addEventListener('change', function(event) {
                                const [file] = event.target.files;
                                if (file) {
                                    document.getElementById('profile_photo_preview').src = URL.createObjectURL(
                                        file);
                                }
                            });
                            </script>
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
            </div>
        </header>

        @if(session('success') || session('error'))
        <div id="notification" class="fixed top-5 right-5 max-w-xs w-full px-4 py-3 rounded shadow-lg text-white
            {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}
            flex items-center space-x-3 z-50" role="alert">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                @if(session('success'))
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                @else
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                @endif
            </svg>
            <span class="flex-1 text-sm font-semibold">
                {{ session('success') ?? session('error') }}
            </span>
            <button onclick="document.getElementById('notification').remove()" class="focus:outline-none"
                aria-label="Close notification">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
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

        <form method="GET" action="{{ route('karyawan.index') }}">
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h2 class="text-xl font-semibold text-blue-700 mb-4">Filter Produk</h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                        <div>
                            <label for="search" class="block text-sm font-medium text-blue-800 mb-1">Pencarian</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="w-full border border-blue-200 bg-white text-blue-900 rounded-xl px-4 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-blue-800 mb-1">Status Stok</label>
                            <select id="stock" name="stock"
                                class="w-full border border-blue-200 bg-white text-blue-900 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Semua</option>
                                <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>Tersedia</option>
                                <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Habis</option>
                            </select>
                        </div>

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
                                class="px-3 py-1 {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full text-sm font-medium">
                                {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tambah ke
                                        Keranjang</label>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="product_type" value="{{ $product->type }}">
                                        <input type="hidden" name="quantity" value="1"> {{-- Default to 1 --}}
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors w-auto"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-cart-plus mr-1 text-xs"></i> Tambah
                                        </button>
                                    </form>
                                </div>
                                <div>
                                    {{-- New Details Button --}}
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lihat Detail</label>
                                    <a href="{{ route('karyawan.show', ['type' => $product->type, 'id' => $product->id]) }}"
                                        class="inline-flex items-center px-3 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors w-auto">
                                        <i class="fas fa-info-circle mr-1 text-xs"></i> Detail Penjualan
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
                        <h3 class="text-xl font-bold mb-4">Toko Bangunan Gaya Baru</h3>
                        <p class="text-blue-200">Satu langkah kecil untuk kebutuhan rumah tangga kamu untuk
                            awet sepanjang masa.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Quick Links</h4>
                        <div class="mt-4 aspect-w-16 aspect-h-7 w-full rounded-lg overflow-hidden shadow-lg">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4012.7261246235303!2d108.1106294!3d-7.458199599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e65ffbab6119a4b%3A0xc3368b1c1ac59b2d!2sMIS%20Cihuni!5e1!3m2!1sid!2sid!4v1750166930800!5m2!1sid!2sid"
                                width="100%" height="auto" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Categories</h4>
                        <ul class="space-y-2">
                            <li><a href="/karyawan?category=material"
                                    class="hover:text-blue-200 transition">Materials</a></li>
                            <li><a href="/karyawan?category=electrical"
                                    class="hover:text-blue-200 transition">Electrical</a></li>
                            <li><a href="/karyawan?category=paint" class="hover:text-blue-200 transition">Plumbing</a>
                            </li>
                            <li><a href="/karyawan?category=plumbing" class="hover:text-blue-200 transition">Paint &
                                    Decor</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Contact Us</h4>
                        <address class="not-italic">
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <a href="https://maps.app.goo.gl/1KUwrYJTRp1euzqHA?g_st=aw" target="_blank"
                                    class="hover:underline text-white hover:text-blue-200">
                                    kp kubang Rt 002 Rw 001 desa leuwidulang,kecamatan sodonghilir, kabupaten
                                    tasikmalaya, provinsi jawa Barat
                                </a>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-phone mr-2"></i>
                                <a href="https://wa.me/+6282363328780" target="_blank"
                                    class="hover:underline text-white hover:text-blue-200">
                                    +6282363328780
                                </a>
                            </p>
                            <p class="mb-2"><i class="fas fa-envelope mr-2"></i> tb.gayabaru99@gmail.com</p>
                        </address>
                    </div>
                </div>
                <div class="border-t border-blue-700 mt-8 pt-6 text-center text-sm text-blue-200">
                    <p>&copy; 2025 Gaya Baru.</p>
                </div>
            </div>
        </footer>

        <script>
        // No need for product price and quantity logic here anymore, it's moved to cart page.
        // The existing JS for updateModal and profile photo preview remains relevant.
        </script>
    </div>
</body>

</html>