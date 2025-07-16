<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History for {{ $product->name }}</title>
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
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <a href="{{ route('karyawan.index') }}">
                    <div class="flex items-center w-14 h-14 space-x-2">
                        <img src="{{ asset('try.png') }}" class="w-15 h-15 text-2xl" alt="">
                    </div>
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
                                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    </div>

                                    <div>
                                        <label for="role"
                                            class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                        <select name="role" id="role"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                            disabled>
                                            <option value="user" {{ Auth::user()->role == 'user' ? 'selected' : '' }}>
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
        </header>

        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 card-hover">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-blue-800">Informasi Produk</h2>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
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
                                        <p class="text-gray-600">{{ $product->description }}</p>
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
                </div>

                <div>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden h-full card-hover">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-blue-800">Sales History</h2>
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open"
                                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200">
                                        <i class="fas fa-filter mr-1"></i> Filter
                                    </button>

                                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                                        class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
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
                                    {{-- If using paginate, uncomment this --}}
                                    {{-- {{ $salesHistory->links('pagination::tailwind') }} --}}
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
        // No purchase related JS here anymore, it's moved to cart page.
        // Toggle dropdown for filter remains relevant.
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }
        </script>

</body>

</html>