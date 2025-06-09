<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Paint;
use App\Models\Payment;
use App\Models\Material;
use App\Models\Plumbing;
use App\Models\Electrical;
use Illuminate\Http\Request;

class SaleController extends Controller
{
public function index(Request $request)
{
    $category = $request->input('category');
    $search = $request->input('search');
    $stock = $request->input('stock');

    $collections = [];

    if (!$category || $category === 'material') {
        $collections[] = ['query' => Material::query(), 'type' => 'material'];
    }

    if (!$category || $category === 'electrical') {
        $collections[] = ['query' => Electrical::query(), 'type' => 'electrical'];
    }

    if (!$category || $category === 'paint') {
        $collections[] = ['query' => Paint::query(), 'type' => 'paint'];
    }

    if (!$category || $category === 'plumbing') {
        $collections[] = ['query' => Plumbing::query(), 'type' => 'plumbing'];
    }

    $products = collect();
    foreach ($collections as $collection) {
        $query = $collection['query'];
        $type = $collection['type'];

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        if ($stock === 'in') {
            $query->where('stock', '>', 0);
        } elseif ($stock === 'out') {
            $query->where('stock', '<=', 0);
        }

        $results = $query->select(['id', 'name', 'stock', 'price', 'status', 'description', 'path'])->get();

        // Tambahkan atribut type ke setiap item
        $results->transform(function ($item) use ($type) {
            $item->type = $type;
            return $item;
        });

        $products = $products->merge($results);
    }

    return view('main.sales.index', [
        'products' => $products,
        'filters' => [
            'category' => $category,
            'search' => $search,
            'stock' => $stock
        ]
    ]);
}

public function show($type, $id)
{
    $models = [
        'material' => Material::class,
        'electrical' => Electrical::class,
        'paint' => Paint::class,
        'plumbing' => Plumbing::class,
    ];

    if (!array_key_exists($type, $models)) {
        abort(404, 'Kategori produk tidak ditemukan');
    }

    $modelClass = $models[$type];
    $product = $modelClass::findOrFail($id);

    $payment = Payment::all();

    // Ganti get() jadi paginate(5)
    $salesHistory = $product->sales()->with('payment')->latest()->paginate(4);

    // Hitung total sales berdasarkan data keseluruhan, jadi ambil semua tanpa paginate
    $totalSales = $product->sales()->sum('price');

    return view('main.sales.sales', compact('product', 'type', 'payment', 'salesHistory', 'totalSales'));
}

public function filter(Request $request, $type, $id)
{
    $models = [
        'material' => Material::class,
        'electrical' => Electrical::class,
        'paint' => Paint::class,
        'plumbing' => Plumbing::class,
    ];

    if (!array_key_exists($type, $models)) {
        abort(404, 'Kategori produk tidak ditemukan');
    }

    $modelClass = $models[$type];
    $product = $modelClass::findOrFail($id);
    $payment = Payment::all();

    $query = $product->sales()->with('payment')->latest();

    if ($request->has('payment_id')) {
        $query->where('payment_id', $request->payment_id);
    }

    $salesHistory = $query->get();

    $totalSales = $salesHistory->sum('price');

    return view('main.sales.sales', compact('product', 'type', 'payment', 'salesHistory', 'totalSales'));
}


public function store(Request $request)
{
    $request->validate([
    'type' => 'required|in:material,electrical,paint,plumbing',
    'product_id' => 'required|integer',
    'quantity' => 'required|integer|min:1',
    'price' => 'required|numeric|min:0',
    'payment_id' => 'required|exists:payments,id',
    ]);

    $models = [
        'material' => Material::class,
        'electrical' => Electrical::class,
        'paint' => Paint::class,
        'plumbing' => Plumbing::class,
    ];

    $type = $request->input('type');
    $productId = $request->input('product_id');

    $modelClass = $models[$type];
    $product = $modelClass::findOrFail($productId);

    // Cek apakah stok cukup
    if ($product->stock < $request->input('quantity')) {
        return redirect()->back()->with('error', 'Stok tidak mencukupi untuk melakukan penjualan.');
    }

    $sale = new Sale([
    'quantity' => $request->input('quantity'),
    'price' => $request->input('price'),
    'payment_id' => $request->input('payment_id'),
    ]);

    $product->sales()->save($sale);

    // Kurangi stok setelah penjualan berhasil
    $product->stock -= $request->input('quantity');
    $product->save();

    return redirect()->back()->with('success', 'Penjualan berhasil disimpan dan stok diperbarui.');
}

}