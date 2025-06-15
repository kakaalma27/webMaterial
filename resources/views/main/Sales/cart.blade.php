<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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

                        window.addEventListener('click', function(e) {
                            const modal = document.getElementById('updateModal');
                            if (e.target === modal) closeModal();
                        });

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
            <h1 class="text-3xl font-bold text-blue-800 mb-8">Keranjang Belanja</h1>

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
                <button onclick="document.getElementById('notification').remove()" class="focus:outline-none"
                    aria-label="Close notification">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
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

            @if ($items->isEmpty())
            <div class="bg-white p-6 rounded-xl shadow-md text-center">
                <p class="text-gray-500 text-lg">Keranjang belanja Anda kosong. <a href="{{ route('karyawan.index') }}"
                        class="text-blue-600 hover:underline">Mulai belanja sekarang!</a></p>
            </div>
            @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    @foreach ($items as $item)
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-6">
                        <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->name }}"
                            class="w-24 h-24 object-cover rounded-lg shadow-sm">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $item->name }}</h2>
                            <p class="text-gray-600">Kategori: {{ ucfirst($item->product_type) }}</p>
                            <p class="text-lg font-bold text-blue-600 mt-1">
                                Rp{{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <form action="{{ route('cart.updateQuantity') }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cart_item_id" value="{{ $item->cart_item_id }}">
                                <button type="button"
                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.parentNode.submit();"
                                    class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                    max="{{ $item->available_stock }}"
                                    class="w-16 text-center border-t border-b border-gray-300 py-1 mx-1 rounded-md"
                                    onchange="this.form.submit()">
                                <button type="button"
                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.parentNode.submit();"
                                    class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="cart_item_id" value="{{ $item->cart_item_id }}">
                                <button type="submit"
                                    class="p-2 text-red-600 hover:bg-red-100 rounded-full focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-blue-800 mb-6">Ringkasan Pesanan</h2>
                        <div class="space-y-3 mb-6">
                            @foreach ($items as $item)
                            <div class="flex justify-between text-gray-700">
                                <span>{{ $item->name }} ({{ $item->quantity }}x)</span>
                                <span>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <div
                                class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold text-blue-800">
                                <span>Total:</span>
                                <span>Rp{{ number_format($totalCartPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('karyawan.store') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="payment" class="block text-sm font-medium text-gray-700 mb-1">Metode
                                    Pembayaran</label>
                                <select name="payment_id" id="payment" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach ($paymentMethods as $payment)
                                    <option value="{{ $payment->id }}">{{ $payment->name }} - {{ $payment->number }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-money-check-alt mr-2"></i> Lanjutkan Pembayaran
                            </button>
                            <a href="{{ route('karyawan.index') }}"
                                class="w-full inline-block text-center mt-4 px-6 py-3 border border-red-600 text-red-600 font-medium rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Lanjutkan Belanja
                            </a>
                        </form>
                    </div>
                </div>
            </div>
            @endif
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
                        <div class="mt-4 aspect-w-16 aspect-h-7 w-full rounded-lg overflow-hidden shadow-lg">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.250567675924!2d-122.41941558468128!3d37.7749294797582!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858064b1d6f4d1%3A0x6e7b7f1e7f9f7f9f!2sYour%20Location!5e0!3m2!1sen!2sus!4v1678888888888!5m2!1sen!2sus"
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
                                <a href="https://maps.app.goo.gl/cBfx5tweRvKUS3L99" target="_blank"
                                    class="hover:underline text-white hover:text-blue-200">
                                    123 Builder St, Construction City
                                </a>
                            </p>
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
    </div>
</body>

</html>