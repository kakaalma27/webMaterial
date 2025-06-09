@extends('layout.pemilik')

@section('title', 'History Payment')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">History Payment</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">History Management</h2>
        <div class="flex space-x-2">
            <!-- Search Form -->
            <form action="{{ route('admin.history') }}" method="GET" class="flex space-x-2 items-center">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                        class="pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>

                <select name="filter"
                    class="py-2 px-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Filter Periode</option>
                    <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
            </form>

            <!-- Add Payment Button -->
            <div class="relative inline-block text-left">
                <button onclick="toggleExportDropdown()" type="button"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 focus:outline-none"
                    id="exportButton">
                    Export
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="exportDropdown"
                    class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="exportButton">
                        <a href="{{ route('admin.pdf') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Export to
                            PDF</a>
                        <a href="{{ route('admin.excel') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Export to
                            Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto sm:overflow-x-visible">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe
                        Produk
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pembayaran
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sales as $sale)
                <tr>
                    <td class="px-2 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-2 py-4 whitespace-nowrap">{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                    <td class="px-2 py-4 whitespace-nowrap">{{ $sale->productable->name ?? '-' }}</td>
                    <td class="px-2 py-4 whitespace-nowrap">{{ class_basename($sale->productable_type) }}</td>
                    <td class="px-2 py-4 whitespace-nowrap">{{ $sale->quantity }}</td>
                    <td class="px-2 py-4 whitespace-nowrap">Rp{{ number_format($sale->price, 0, ',', '.') }}</td>
                    <td class="px-2 py-4 whitespace-nowrap">{{ $sale->payment->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">Belum ada penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <div class="text-sm text-gray-700">
            Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
        </div>
        <div>
            {{ $sales->links() }}
        </div>
    </div>

</div>

<script>
function toggleExportDropdown() {
    const dropdown = document.getElementById('exportDropdown');
    dropdown.classList.toggle('hidden');
}

// Optional: Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const button = document.getElementById('exportButton');
    const dropdown = document.getElementById('exportDropdown');
    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
@endsection