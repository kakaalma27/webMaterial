<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();

        $query = Material::query()->where('user_id', $user_id); // Filter berdasarkan user yang login

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

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
                $query->latest();
                break;
        }

        $Materials = $query->paginate(10)->withQueryString();

        return view('main.material.index', compact('Materials'));
    }

    public function create()
    {
        return view('main.material');
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

        $path = $request->file('image')->store('Materials', 'public');
        $user_id = auth()->id();
        Material::create([
            'user_id' => $user_id,
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
        $Material = Material::findOrFail($id);
        return view('main.material', compact('Material'));
    }

    public function edit($id)
    {
        $Material = Material::findOrFail($id);
        return view('main.material', compact('Material'));
    }

    public function update(Request $request, $id)
    {
        $Material = Material::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($Material->path && Storage::disk('public')->exists($Material->path)) {
                Storage::disk('public')->delete($Material->path);
            }
            $validated['path'] = $request->file('image')->store('Materials', 'public');
        }

        $Material->update($validated);

        return redirect()->route('materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy($id)
    {
        $Material = Material::findOrFail($id);
        if ($Material->path && Storage::disk('public')->exists($Material->path)) {
            Storage::disk('public')->delete($Material->path);
        }
        $Material->delete();

        return redirect()->route('materials.index')->with('success', 'Material deleted successfully.');
    }
}
