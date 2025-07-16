<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
    <!-- Add this to allow per-page script injection -->

    <style>
    [x-cloak] {
        display: none;
    }

    /* Custom styles */
    .gradient-bg {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    }

    .sidebar {
        transition: all 0.3s ease;
    }

    .input-highlight {
        transition: all 0.3s ease;
    }

    .input-highlight:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }

    .modal {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .modal-hidden {
        opacity: 0;
        transform: scale(0.9);
        pointer-events: none;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>

<body class="bg-blue-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="{{ url('/admin/dashboard') }}">
                    <div class="flex items-center w-14 h-14 space-x-2">
                        <img src="{{ asset('try.png') }}" class="w-15 h-15 text-2xl" alt="">
                    </div>
                </a>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="{{ url('/admin/dashboard') }}" class="hover:text-blue-200 transition">Home</a>
                <div x-data="{ open: false }" class="relative inline-block">
                    <button @click="open = !open" class="hover:text-blue-200 transition">Products</button>

                    <div x-show="open" x-cloak @click.away="open = false"
                        class="absolute bg-white shadow-lg mt-2 rounded-lg z-10" x-transition>
                        <ul class="py-2 w-40">
                            <li>
                                <a href="{{ route('materials.index') }}" @click="open = false"
                                    class="block px-4 py-2 text-blue-700 hover:text-white hover:bg-blue-500">Materials</a>
                            </li>
                            <li>
                                <a href="{{ route('electricals.index') }}" @click="open = false"
                                    class="block px-4 py-2 text-blue-700 hover:text-white hover:bg-blue-500">Electricals</a>
                            </li>
                            <li>
                                <a href="{{ route('plumbings.index') }}" @click="open = false"
                                    class="block px-4 py-2 text-blue-700 hover:text-white hover:bg-blue-500">Plumbings</a>
                            </li>
                            <li>
                                <a href="{{ route('paints.index') }}" @click="open = false"
                                    class="block px-4 py-2 text-blue-700 hover:text-white hover:bg-blue-500">Paint &
                                    Decor</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <a href="#contact" class="hover:text-blue-200 transition">Contact</a>
            </div>
            <div class="flex items-center space-x-4">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 border border-white rounded-md font-medium hover:bg-blue-700 transition">
                        Logout
                    </button>
                </form>


                <button class="md:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white shadow-md h-screen sticky top-0 hidden md:block">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">Categories</h2>
            </div>
            <ul class="py-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2 hover:bg-blue-50 font-medium {{ request()->routeIs('dashboard') ? 'text-blue-800 bg-blue-100' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3a2 2 0 01-2-2v-9z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.history') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('admin.history') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l2-2 4 4m1-9h.01M6 3h12a1 1 0 011 1v16a1 1 0 01-1.447.894L12 18l-5.553 2.894A1 1 0 015 20V4a1 1 0 011-1z" />
                        </svg>
                        History of Sales
                    </a>
                </li>
                <li>
                    <a href="{{ route('restock.index') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('restock.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        ReStock
                    </a>
                </li>
                <li>
                    <a href="{{ route('payment.index') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('payment.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9m10 0H5" />
                        </svg>
                        Payment
                    </a>
                </li>

                <li>
                    <a href="{{ route('materials.index') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('materials.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        Building Materials
                    </a>
                </li>

                <li>
                    <a href="{{ route('electricals.index') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('electricals.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Building Electricals
                    </a>
                </li>

                <li>
                    <a href="{{ route('plumbings.index') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('plumbings.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M12 3v4m0 0a4 4 0 01-4 4m4-4a4 4 0 014 4" />
                        </svg>
                        Building Plumbings
                    </a>
                </li>

                <li>
                    <a href="{{ route('paints.index') }}"
                        class="block px-4 py-2 hover:bg-blue-50 {{ request()->routeIs('paints.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-4.553a1 1 0 000-1.414l-2.586-2.586a1 1 0 00-1.414 0L11 6.586M4 20h16M4 20l4.879-4.879a3 3 0 014.242 0L20 20" />
                        </svg>
                        Building Paint & Decor
                    </a>
                </li>
            </ul>
            <div class="p-4 border-t">
                <h2 class="text-lg font-semibold">Account</h2>
                <div class="mt-2">
                    <a href="{{ route('user') }}"
                        class="block py-2 hover:bg-blue-50 {{ request()->routeIs('user.*') ? 'text-blue-800 bg-blue-100 font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        User Management
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </div>
    <footer id="contact" class="bg-gradient-to-t from-blue-900 to-blue-700 text-white py-8">
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
                        <li><a href="{{ route('materials.index') }}"
                                class="hover:text-blue-200 transition">Materials</a></li>
                        <li><a href="{{ route('electricals.index') }}"
                                class="hover:text-blue-200 transition">Electrical</a></li>
                        <li><a href="{{ route('plumbings.index') }}" class="hover:text-blue-200 transition">Plumbing</a>
                        </li>
                        <li><a href="{{ route('paints.index') }}" class="hover:text-blue-200 transition">Paint &
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
    // Modal functionality
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const loginCloseBtn = document.getElementById('loginCloseBtn');
    const registerCloseBtn = document.getElementById('registerCloseBtn');
    const switchToRegister = document.getElementById('switchToRegister');
    const switchToLogin = document.getElementById('switchToLogin');

    function toggleModal(modal) {
        modal.classList.toggle('opacity-0');
        modal.classList.toggle('modal-hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    loginBtn.addEventListener('click', () => {
        toggleModal(loginModal);
    });

    registerBtn.addEventListener('click', () => {
        toggleModal(registerModal);
    });

    loginCloseBtn.addEventListener('click', () => {
        toggleModal(loginModal);
    });

    registerCloseBtn.addEventListener('click', () => {
        toggleModal(registerModal);
    });

    switchToRegister.addEventListener('click', (e) => {
        e.preventDefault();
        toggleModal(loginModal);
        setTimeout(() => toggleModal(registerModal), 300);
    });

    switchToLogin.addEventListener('click', (e) => {
        e.preventDefault();
        toggleModal(registerModal);
        setTimeout(() => toggleModal(loginModal), 300);
    });

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            toggleModal(loginModal);
        }
        if (e.target === registerModal) {
            toggleModal(registerModal);
        }
    });

    // Sample inventory data (would normally come from API)
    const inventoryData = [{
            id: 'BM-1001',
            name: 'Concrete Mix 50lb',
            category: 'Building Materials',
            stock: 48,
            price: 12.99,
            status: 'Active'
        },
        {
            id: 'BM-1002',
            name: 'Drywall Sheet 4x8',
            category: 'Building Materials',
            stock: 5,
            price: 15.49,
            status: 'Active'
        },
        {
            id: 'BM-1003',
            name: 'Roofing Shingles',
            category: 'Building Materials',
            stock: 0,
            price: 34.99,
            status: 'Inactive'
        },
        // More items would be here in a real application
    ];

    // This would be used to dynamically populate the inventory table
    function populateInventoryTable() {
        // In a real app, this would fetch data and update the table
        console.log('Inventory data loaded:', inventoryData);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        populateInventoryTable();
    });
    </script>
</body>

</html>