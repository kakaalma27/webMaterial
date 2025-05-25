<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
public function index(Request $request)
{
    $query = Material::query();

    // Optional search
    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Sorting
    switch ($request->sort) {
        case 'price_asc':
            $query->orderBy('price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
        case 'newest':
            $query->orderBy('created_at', 'desc');
            break;
        default:
            $query->latest(); // default sorting
            break;
    }

    $materials = $query->paginate(10)->withQueryString();

    return view('main.material.index', compact('materials'));
}




    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('image')->store('materials', 'public');

        Material::create([
            'name' => $validated['name'],
            'stock' => $validated['stock'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'path' => $path,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material created successfully.');
    }

    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($material->path && Storage::disk('public')->exists($material->path)) {
                Storage::disk('public')->delete($material->path);
            }
            $validated['path'] = $request->file('image')->store('electricals', 'public');
        }

        $material->update($validated);

        return redirect()->route('materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        if ($material->path && Storage::disk('public')->exists($material->path)) {
            Storage::disk('public')->delete($material->path);
        }
        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Material deleted successfully.');
    }
}
