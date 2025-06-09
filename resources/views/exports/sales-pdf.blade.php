<!DOCTYPE html>
<html>

<head>
    <title>Sales PDF</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 6px;
        text-align: left;
    }
    </style>
</head>

<body>
    <h2>History Penjualan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Tipe Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $sale->productable->name ?? '-' }}</td>
                <td>{{ class_basename($sale->productable_type) }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>Rp{{ number_format($sale->price, 0, ',', '.') }}</td>
                <td>{{ $sale->payment->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>