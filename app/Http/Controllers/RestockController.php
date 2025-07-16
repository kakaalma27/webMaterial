<?php

namespace App\Http\Controllers;

use App\Models\Paint;
use App\Models\Material;
use App\Models\Plumbing;
use App\Models\Electrical;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class RestockController extends Controller
{
    /**
     * Defines the mapping between product type strings and their corresponding Eloquent models.
     * @var array
     */
    private $models = [
        'material' => Material::class,
        'electrical' => Electrical::class,
        'paint' => Paint::class,
        'plumbing' => Plumbing::class,
    ];

    /**
     * Displays a listing of all products with filtering, searching, and sorting capabilities.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $allProducts = new Collection();
        $search = $request->input('search');
        $sort = $request->input('sort');
        $productTypeFilter = $request->input('type'); // Filter by product type (e.g., 'material', 'electrical')

        // Iterate through each product model to fetch and combine products
        foreach ($this->models as $type => $modelClass) {
            $query = $modelClass::query();

            // Apply search filter if a search query is present
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            }

            // Get products and add a 'product_type' attribute to each item
            // This attribute is crucial for distinguishing product types in the Blade view
            $products = $query->get()->map(function ($item) use ($type) {
                $item->product_type = $type;
                return $item;
            });

            // Merge products from the current model into the overall collection
            $allProducts = $allProducts->merge($products);
        }

        // Apply product type filter if a specific type is selected (and not 'all')
        if ($productTypeFilter && $productTypeFilter !== 'all') {
            $allProducts = $allProducts->filter(function ($product) use ($productTypeFilter) {
                return $product->product_type === $productTypeFilter;
            });
        }

        // Apply sorting based on the 'sort' parameter
        if ($sort) {
            if ($sort === 'price_asc') {
                $allProducts = $allProducts->sortBy('price');
            } elseif ($sort === 'price_desc') {
                $allProducts = $allProducts->sortByDesc('price');
            } elseif ($sort === 'newest') {
                $allProducts = $allProducts->sortByDesc('created_at');
            }
        } else {
            // Default sort by newest if no sort option is specified
            $allProducts = $allProducts->sortByDesc('created_at');
        }

        // Manual Pagination for the combined collection
        $perPage = 10; // Number of items to display per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Get the current page number
        // Slice the collection to get items for the current page
        $currentItems = $allProducts->slice(($currentPage - 1) * $perPage, $perPage)->all();
        // Create a LengthAwarePaginator instance
        $products = new LengthAwarePaginator($currentItems, $allProducts->count(), $perPage, $currentPage, [
            'path' => $request->url(), // Set the base URL for pagination links
            'query' => $request->query(), // Preserve existing query parameters in pagination links
        ]);

        // Pass the paginated products to the view
        return view('main.restock.index', compact('products'));
    }

    /**
     * Stores a newly created product in the database.
     * Handles different product types based on the 'product_type' input.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'product_type' => 'required|string|in:material,electrical,paint,plumbing',
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        ]);

        // Determine the correct model class based on the 'product_type'
        $modelClass = $this->models[$request->product_type];
        $product = new $modelClass();

        // Assign common product attributes
        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->status = $request->status;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/products');
            // Store the path relative to the 'storage' disk (without 'public/')
            $product->path = str_replace('public/', '', $path);
        }

        $product->save(); // Save the new product to the database

        return redirect()->route('restock.index')->with('success', 'Product added successfully!');
    }

    /**
     * Updates the specified product in the database.
     * Handles different product types based on the 'product_type' input.
     *
     * @param Request $request
     * @param int $id The ID of the product to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $request->validate([
            'product_type' => 'required|string|in:material,electrical,paint,plumbing',
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Determine the correct model class and find the product
        $modelClass = $this->models[$request->product_type];
        $product = $modelClass::findOrFail($id); // Find the product by ID or throw 404

        // Update common product attributes
        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->status = $request->status;

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->path && Storage::disk('public')->exists($product->path)) {
                Storage::disk('public')->delete($product->path);
            }
            $path = $request->file('image')->store('public/products');
            $product->path = str_replace('public/', '', $path);
        }

        $product->save(); // Save the updated product

        return redirect()->route('restock.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Removes the specified product from the database.
     * Handles different product types based on the 'product_type' input.
     *
     * @param Request $request
     * @param int $id The ID of the product to delete.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        // Validate that product_type is provided for deletion
        $request->validate([
            'product_type' => 'required|string|in:material,electrical,paint,plumbing',
        ]);

        // Determine the correct model class and find the product
        $modelClass = $this->models[$request->product_type];
        $product = $modelClass::findOrFail($id);

        // Delete associated image file if it exists
        if ($product->path && Storage::disk('public')->exists($product->path)) {
            Storage::disk('public')->delete($product->path);
        }

        $product->delete(); // Delete the product record

        return redirect()->route('restock.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Fetches details of a specific product via AJAX for dynamic modal population.
     *
     * @param Request $request
     * @param string $type The type of the product (e.g., 'material', 'electrical').
     * @param int $id The ID of the product.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductDetails(Request $request, $type, $id)
    {
        // Validate the product type
        if (!array_key_exists($type, $this->models)) {
            return response()->json(['error' => 'Invalid product type'], 400);
        }

        // Find the product and add its type to the response
        $modelClass = $this->models[$type];
        $product = $modelClass::findOrFail($id);
        $product->product_type = $type;

        return response()->json($product);
    }
}
