<?php

namespace App\Http\Controllers;

use App\Models\Electrical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElectricalController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();

        $query = Electrical::query()->where('user_id', $user_id); // Filter berdasarkan user yang login

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

        $Electricals = $query->paginate(10)->withQueryString();

        return view('main.electrical.index', compact('Electricals'));
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

        $path = $request->file('image')->store('Electricals', 'public');
        $user_id = auth()->id();
        Electrical::create([
            'user_id' => $user_id,
            'name' => $validated['name'],
            'stock' => $validated['stock'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'path' => $path,
        ]);

        return redirect()->route('electricals.index')->with('success', 'Electrical created successfully.');
    }

    public function show($id)
    {
        $Electrical = Electrical::findOrFail($id);
        return view('main.electrical', compact('Electrical'));
    }

    public function edit($id)
    {
        $Electrical = Electrical::findOrFail($id);
        return view('main.electrical', compact('Electrical'));
    }

    public function update(Request $request, $id)
    {
        $Electrical = Electrical::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($Electrical->path && Storage::disk('public')->exists($Electrical->path)) {
                Storage::disk('public')->delete($Electrical->path);
            }
            $validated['path'] = $request->file('image')->store('electricals', 'public');
        }

        $Electrical->update($validated);

        return redirect()->route('electricals.index')->with('success', 'Electrical updated successfully.');
    }

    public function destroy($id)
    {
        $Electrical = Electrical::findOrFail($id);
        if ($Electrical->path && Storage::disk('public')->exists($Electrical->path)) {
            Storage::disk('public')->delete($Electrical->path);
        }
        $Electrical->delete();

        return redirect()->route('electricals.index')->with('success', 'Electrical deleted successfully.');
    }
}
