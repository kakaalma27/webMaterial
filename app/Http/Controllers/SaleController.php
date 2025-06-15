<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Paint;
use App\Models\Payment;
use App\Models\Material;
use App\Models\Plumbing;
use App\Models\Electrical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Ensure Session facade is imported

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
        $profile_photo_path = User::where('id', auth()->id())->value('profile_photo_path');

        return view('main.sales.index', [
            'products' => $products,
            'profile' => $profile_photo_path,
            'filters' => [
                'category' => $category,
                'search' => $search,
                'stock' => $stock
            ]
        ]);
    }

    // New method to add items to cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_type' => 'required|string',
            'quantity' => 'required|integer|min:1', // Allow adding specific quantity to cart
        ]);

        $productId = $request->input('product_id');
        $productType = $request->input('product_type');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        // Unique identifier for the product in the cart (product_type + product_id)
        $cartItemId = $productType . '_' . $productId;

        // Find the product to check stock
        $models = [
            'material' => Material::class,
            'electrical' => Electrical::class,
            'paint' => Paint::class,
            'plumbing' => Plumbing::class,
        ];

        if (!array_key_exists($productType, $models)) {
            return redirect()->back()->with('error', 'Invalid product type.');
        }

        $modelClass = $models[$productType];
        $product = $modelClass::findOrFail($productId);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk ditambahkan ke keranjang.');
        }


        if (isset($cart[$cartItemId])) {
            // If item already in cart, update quantity
            $newQuantity = $cart[$cartItemId]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                 return redirect()->back()->with('error', 'Menambahkan jumlah ini akan melebihi stok yang tersedia.');
            }
            $cart[$cartItemId]['quantity'] = $newQuantity;
        } else {
            // Add new item to cart
            $cart[$cartItemId] = [
                "product_id" => $productId,
                "product_type" => $productType,
                "quantity" => $quantity,
                "price" => $product->price,
                "name" => $product->name,
                "path" => $product->path, // Assuming 'path' is the image path
            ];
        }

        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Method to display cart contents
    public function showCart()
    {
        $cart = Session::get('cart', []);
        $items = collect($cart)->map(function ($item, $cartItemId) {
            $model = match ($item['product_type']) {
                'material' => Material::class,
                'electrical' => Electrical::class,
                'paint' => Paint::class,
                'plumbing' => Plumbing::class,
                default => null,
            };

            if ($model) {
                $product = $model::find($item['product_id']);
                if ($product) {
                    return (object) [ // Cast to object for easy access in Blade
                        'cart_item_id' => $cartItemId,
                        'product_id' => $item['product_id'],
                        'product_type' => $item['product_type'],
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'path' => $item['path'],
                        'available_stock' => $product->stock // Pass actual stock
                    ];
                }
            }
            return null;
        })->filter(); // Remove any null entries if product not found

        $paymentMethods = Payment::all(); // Assuming you want payment methods on the cart/checkout page

        $totalCartPrice = $items->sum(fn($item) => $item->price * $item->quantity);

        return view('main.sales.cart', compact('items', 'paymentMethods', 'totalCartPrice'));
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
    // New method to remove item from cart
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|string',
        ]);

        $cart = Session::get('cart', []);
        $cartItemId = $request->input('cart_item_id');

        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            Session::put('cart', $cart);
            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    // New method to update quantity in cart
    public function updateCartQuantity(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);
        $cartItemId = $request->input('cart_item_id');
        $newQuantity = $request->input('quantity');

        if (isset($cart[$cartItemId])) {
            $productId = $cart[$cartItemId]['product_id'];
            $productType = $cart[$cartItemId]['product_type'];

            $models = [
                'material' => Material::class,
                'electrical' => Electrical::class,
                'paint' => Paint::class,
                'plumbing' => Plumbing::class,
            ];

            $modelClass = $models[$productType];
            $product = $modelClass::findOrFail($productId);

            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Kuantitas melebihi stok yang tersedia (' . $product->stock . ').');
            }

            $cart[$cartItemId]['quantity'] = $newQuantity;
            Session::put('cart', $cart);
            return redirect()->back()->with('success', 'Kuantitas keranjang berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        $models = [
            'material' => Material::class,
            'electrical' => Electrical::class,
            'paint' => Paint::class,
            'plumbing' => Plumbing::class,
        ];

        foreach ($cart as $cartItemId => $item) {
            $productType = $item['product_type'];
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            if (!array_key_exists($productType, $models)) {
                return redirect()->back()->with('error', 'Tipe produk tidak valid di keranjang.');
            }

            $modelClass = $models[$productType];
            $product = $modelClass::findOrFail($productId);

            // Re-check stock just before processing the sale to prevent race conditions
            if ($product->stock < $quantity) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk ' . $product->name . '. Hanya tersedia ' . $product->stock . ' unit.');
            }

            $sale = new Sale([
                'quantity' => $quantity,
                'price' => $item['price'] * $quantity, // Calculate total price for this item
                'payment_id' => $request->input('payment_id'),
            ]);

            $product->sales()->save($sale);

            // Decrease stock
            $product->stock -= $quantity;
            $product->save();
        }

        Session::forget('cart'); // Clear the cart after successful purchase

        return redirect()->route('karyawan.index')->with('success', 'Pembelian berhasil diselesaikan!');
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
}