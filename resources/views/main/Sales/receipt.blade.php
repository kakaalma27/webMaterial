<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f4f8;
        /* Light blue-gray background */
    }

    /* Styles for printing */
    @media print {
        body {
            background-color: #fff;
        }

        .no-print {
            display: none;
        }

        .receipt-container {
            box-shadow: none;
            border: 1px solid #ccc;
            /* Add border for print */
            margin: 0;
            padding: 0;
        }
    }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="receipt-container bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-blue-800 mb-2">Toko Bangunan Gaya Baru</h1>
            <p class="text-gray-600 text-sm">Struk Pembelian</p>
            <p class="text-gray-500 text-xs">{{ $transaction_date ?? '' }}</p>
        </div>

        <div class="mb-6 border-b pb-4 border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Detail Pembayaran</h2>
            <div class="flex justify-between mb-1">
                <span class="text-gray-600">Metode Pembayaran:</span>
                <span class="font-medium text-gray-800">{{ $payment_name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Nomor Pembayaran:</span>
                <span class="font-medium text-gray-800">{{ $payment_number ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Item Pembelian</h2>
            <div class="space-y-2">
                @foreach ($purchased_items as $item)
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <p class="text-gray-800 font-medium">{{ $item['name'] }}</p>
                        <p class="text-gray-500 text-sm">{{ $item['quantity'] }} x
                            Rp{{ number_format($item['price_per_unit'], 0, ',', '.') }}</p>
                    </div>
                    <span
                        class="font-semibold text-gray-800">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="border-t pt-4 border-gray-300">
            <div class="flex justify-between items-center text-2xl font-bold text-blue-800">
                <span>Total:</span>
                <span>Rp{{ number_format($total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-8 text-center no-print">
            <button onclick="window.print()"
                class="w-full mb-3 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-print mr-2"></i> Cetak Struk
            </button>
            <a href="{{ route('karyawan.index') }}"
                class="w-full inline-block px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition-colors">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>