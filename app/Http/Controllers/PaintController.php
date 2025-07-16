<?php

namespace App\Http\Controllers;

use App\Models\Paint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaintController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();

        $query = Paint::query()->where('user_id', $user_id); // Filter berdasarkan user yang login

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

        $Paint = $query->paginate(10)->withQueryString();

        return view('main.paint.index', compact('Paint'));
    }

    public function create()
    {
        return view('paints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'color' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('image')->store('Paints', 'public');
        $user_id = auth()->id();
        Paint::create([
            'user_id' => $user_id,
            'name' => $validated['name'],
            'color' => $validated['color'],
            'stock' => $validated['stock'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'path' => $path,
        ]);

        return redirect()->route('paints.index')->with('success', 'Paints created successfully.');
    }

    public function show($id)
    {
        $Paints = Paint::findOrFail($id);
        return view('paints.show', compact('Paint'));
    }

    public function edit($id)
    {
        $Paints = Paint::findOrFail($id);
        return view('paints.edit', compact('Paint'));
    }

    public function update(Request $request, $id)
    {
        $Paints = Paint::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($Paints->path && Storage::disk('public')->exists($Paints->path)) {
                Storage::disk('public')->delete($Paints->path);
            }
            $validated['path'] = $request->file('image')->store('Paints', 'public');
        }

        $Paints->update($validated);

        return redirect()->route('paints.index')->with('success', 'Paints updated successfully.');
    }

    public function destroy($id)
    {
        $Paints = Paint::findOrFail($id);
        if ($Paints->path && Storage::disk('public')->exists($Paints->path)) {
            Storage::disk('public')->delete($Paints->path);
        }
        $Paints->delete();

        return redirect()->route('paints.index')->with('success', 'Paints deleted successfully.');
    }
}
